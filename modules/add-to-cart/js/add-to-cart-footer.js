/* global webigoHelper */
/* jshint latedef:nofunc */
(function (webigoHelper, d, $, wc_cart_fragments_params) {
  const _state = webigoHelper?.stateManager;
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const _session = webigoHelper?.sessionManager;
  const _request = webigoHelper?.httpRequestManager;

  const addToCartFooterArea = _dom.el(".wbg-add-to-cart-footer");
  const bulkAddToCartButton = _dom.el(
    ".wbg-add-to-cart-footer .wbg-bulk-add-to-cart"
  );
  const showCartFooterButton = _dom.el(".wbg-show-cart");

  init();

  function init() {
    _event.attachEvent({
      el: bulkAddToCartButton,
      ev: _event.type.click,
      cb: bulkAddToCartRequest,
    });

    if (showCartFooterButton) {
      showCartFooterButton.addEventListener("click", openMinicart);
    }

    _event.listen({
      ev: "showAddToCartContainer",
      cb: showAddToCartContainer,
    });

    _event.listen({
      ev: "hideAddToCartContainer",
      cb: hideAddToCartContainer,
    });

    _event.listen({
      ev: "handleAddToCartFooterVisibility",
      cb: handleAddToCartFooterVisibility,
    });
  }

  async function bulkAddToCartRequest(e) {
    e.preventDefault();

    const totalCartQty = _state.state?.cartTotals?.qty;
    if (typeof totalCartQty === "undefined" || totalCartQty === 0) {
      return;
    }

    const addToCartActions = new WbgAddToCartActions();
    const wpnonce = d.getElementById("webigo_bulk_add_to_cart_nonce");

    const requestOptions = _request.options;
    const requestData = _request.data;

    handleRequestInit();

    const cartContent = JSON.stringify(_state.state.cart);

    const _requestData = requestData.set({
      action: "webigo_bulk_add_to_cart",
      nonce: wpnonce.value,
      resource: "bulk-add-to-cart",
      cart: cartContent,
    });

    requestOptions.addRequestData(_requestData);

    const url = wc_add_to_cart_params.ajax_url;
    const httpResponse = await fetch(url, requestOptions.get());

    if (!httpResponse.ok) {
      handleResponseFailed();
    }
    const wcResponse = await httpResponse.json();

    if (wcResponse.success === true) {
      handleResponseSuccess();
      updateMiniCart(wcResponse.data);
      updateSessionCartFragments(wcResponse.data);
    }

    if (wcResponse.error || wcResponse.success === false) {
      handleResponseFailed();
    }

    function handleRequestInit() {
      addToCartActions.pending();
    }

    function handleResponseFailed() {
      addToCartActions.idle();
      _event.trigger({ ev: "failedNotification" });
    }

    function handleResponseSuccess() {
      addToCartActions.completed();
      _event.trigger({ ev: "successNotification" });

      setTimeout(function () {
        resetProductQuantityAndValue();
        resetCartState();
        showShowCartButton();
        hideBulkAddToCartButton();
        handleAddToCartFooterVisibility();
      }, 1500);
    }

    function updateSessionCartFragments(data) {
      // inspired by the Woo cart-fragments.js
      if (!data.fragments) {
        return;
      }

      if (!wc_cart_fragments_params) {
        return;
      }

      _session.set(wc_cart_fragments_params.fragment_name, data.fragments);
      _session.set(wc_cart_fragments_params.cart_hash_key, data.cart_hash);
    }

    function updateMiniCart(data) {
      if (!data.fragments) {
        return;
      }

      const fragmentsData = data.fragments;

      Object.keys(fragmentsData).forEach((key) => {
        $(key).replaceWith(fragmentsData[key]);
      });
    }
  }

  function resetProductQuantityAndValue() {
    _event.trigger({ ev: "resetProductQuantityAndValue" });
  }

  function resetCartState() {
    _state.resetItemState("cart");
    _state.resetItemState("cartTotals");
  }

  function showShowCartButton() {
    _dom.show(showCartFooterButton);
  }

  function hideBulkAddToCartButton() {
    _dom.hide(bulkAddToCartButton);
  }

  function showAddToCartContainer({ domTarget }) {
    _dom.show(domTarget);
  }

  function hideAddToCartContainer({ domTarget }) {
    _dom.hide(domTarget);
  }

  function showAddToCartFooter({ domTarget }) {
    _dom.show(domTarget);
    _dom.hide(showCartFooterButton);
    _dom.show(bulkAddToCartButton);
  }

  function hideAddToCartFooter({ domTarget }) {
    _dom.hide(domTarget);
  }

  function handleAddToCartFooterVisibility() {
    const { qty: cartTotalQty } = _state.state.cartTotals;

    if (cartTotalQty > 0) {
      showAddToCartFooter({ domTarget: addToCartFooterArea });
    }

    if (cartTotalQty === 0 || typeof cartTotalQty === "undefined") {
      hideAddToCartFooter({ domTarget: addToCartFooterArea });
    }
  }

  function openMinicart(e) {
    e.preventDefault();
    const miniCartDetail = d.querySelectorAll(
      ".mini-cart-wrapper .cart-detail"
    )[0];

    if (miniCartDetail) {
      miniCartDetail.classList.add("active");
    }

    // Bricks has a click event attached to the parent
    // use to avoid collisions with that event
    e.stopPropagation();

    _dom.scrollToTop();

    _dom.hide(addToCartFooterArea);
    _dom.hide(showCartFooterButton);
    _dom.show(bulkAddToCartButton);

    _event.trigger({ ev: "minicartOpened" });
  }
})(webigoHelper, document, jQuery, wc_cart_fragments_params);

/**
 * Class to handle add to cart area
 */

class WbgAddToCartActions {
  prodId = false;
  catId = false;
  element = false;
  _dom = false;

  constructor(prodId, catId) {
    this.prodId = prodId;
    this.catId = catId;
    this._dom = webigoHelper?.domManager;

    this.init();
  }

  init = () => {
    if (this.prodId && this.catId) {
      this.getElement();
    }

    if (!this.prodId && !this.catId) {
      this.getFooterElement();
    }
  };

  getElement = () => {
    this.element = document.querySelectorAll(
      ".wbg-add-to-cart[data-product-id='" +
        this.prodId +
        "'][data-category-id='" +
        this.catId +
        "']"
    )[0];
  };

  getFooterElement = () => {
    this.element = document.querySelectorAll(".wbg-add-to-cart-footer")[0];
  };

  showArea = () => {
    this._dom.show(this.element);
  };

  hideArea = () => {
    this._dom.hide(this.element);
  };

  idle = () => {
    this.element.setAttribute("data-action-state", "idle");
  };

  pending = () => {
    this.element.setAttribute("data-action-state", "pending");
  };

  completed = () => {
    this.element.setAttribute("data-action-state", "completed");
  };
}
