/* global webigoHelper */
/* jshint latedef:nofunc */
(function (webigoHelper, d, $, wc_cart_fragments_params) {
  const _state = webigoHelper?.stateManager;
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const _session = webigoHelper?.sessionManager;
  const _request = webigoHelper?.httpRequestManager;

  const addToCartButtons = d.querySelectorAll(".wbg-add-to-cart-button");
  const addToCartFooterArea = _dom.el(".wbg-add-to-cart-footer");
  const bulkAddToCartButton = _dom.el(
    ".wbg-add-to-cart-footer .wbg-bulk-add-to-cart"
  );
  const showCartFooterButton = _dom.el(".wbg-show-cart");

  init();

  function init() {
    _event.bulkAttachEvent({
      elements: addToCartButtons,
      ev: _event.type.click,
      cb: addToCart,
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

  async function addToCart(e) {
    e.preventDefault();
    const { prodId, catId } = _dom.getElementAttribute(this);
    const addToCartNotification = new WbgAddToCartNotification(prodId, catId);
    const addToCartActions = new WbgAddToCartActions(prodId, catId);
    const requestOptions = new WbgAddToCartRequestOptions(prodId, catId);

    handleRequestInit();

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
      addToCartNotification.hide();
    }

    function handleResponseFailed() {
      addToCartActions.idle();
      addToCartNotification.showFailed();
    }

    function handleResponseSuccess() {
      addToCartActions.completed();
      addToCartNotification.showSuccess();
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
 * Class to handle the notifications
 */
class WbgAddToCartNotification {
  prodId = false;
  catId = false;
  successElement = false;
  failedElement = false;

  constructor(prodId, catId) {
    this.prodId = prodId;
    this.catId = catId;
    this._dom = webigoHelper?.domManager;

    this.init();
  }

  init = () => {
    if (this.prodId && this.catId) {
      this.getInlineSuccessElement();
      this.getInlineFailedElement();
    }

    if (!this.prodId && !this.catId) {
      this.getFooterSuccessElement();
      this.getFooterFailedElement();
    }
  };

  getInlineSuccessElement = () => {
    this.successElement = document.querySelectorAll(
      ".wbg-add-to-cart[data-product-id='" +
        this.prodId +
        "'][data-category-id='" +
        this.catId +
        "'] .wbg-add-to-cart-notification-success"
    )[0];
  };

  getInlineFailedElement = () => {
    this.failedElement = document.querySelectorAll(
      ".wbg-add-to-cart[data-product-id='" +
        this.prodId +
        "'][data-category-id='" +
        this.catId +
        "'] .wbg-add-to-cart-notification-failed"
    )[0];
  };

  getFooterSuccessElement = () => {
    this.successElement = document.querySelectorAll(
      ".wbg-add-to-cart-footer-button .wbg-add-to-cart-notification-success"
    )[0];
  };

  getFooterFailedElement = () => {
    this.failedElement = document.querySelectorAll(
      ".wbg-add-to-cart-footer-button .wbg-add-to-cart-notification-failed"
    )[0];
  };

  showFailed = () => {
    this._dom.show(this.failedElement);
  };

  showSuccess = () => {
    this._dom.show(this.successElement);
  };

  hide = () => {
    if (this.successElement) {
      this._dom.hide(this.successElement);
    }

    if (this.failedElement) {
      this._dom.hide(this.failedElement);
    }
  };
}

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

class WbgAddToCartRequestData {
  action = "webigo_ajax_add_to_cart";

  constructor(prodId, catId) {
    this.prodId = prodId;
    this.catId = catId;

    this.init();
  }

  init = () => {
    this.getProductQuantityElement();
    this.getProductQuantity();
    this.getNonceElement();
    this.getNonce();
  };

  getProductQuantityElement = () => {
    this.productQuantity = document.querySelectorAll(
      ".wbg-product-input-quantity[data-product-id='" +
        this.prodId +
        "'][data-category-id='" +
        this.catId +
        "']"
    )[0];
  };

  getProductQuantity = () => {
    this.productQuantityValue = this.productQuantityElement
      ? this.productQuantityElement.value
      : false;
  };

  getNonceElement = () => {
    this.wpNonceElement = document.querySelectorAll(
      ".wbg-add-to-cart[data-product-id='" +
        this.prodId +
        "'][data-category-id='" +
        this.catId +
        "'] input[name='webigo_woo_add_to_cart_nonce']"
    )[0];
  };

  getNonce = () => {
    this.wpNonceValue = this.wpNonceElement
      ? this.wpNonceElement.getAttribute("value")
      : false;
  };

  getPostData = () => {
    return new URLSearchParams({
      action: this.action,
      product_id: this.prodId,
      product_sku: "",
      quantity: this.productQuantityValue,
      nonce: this.wpNonceValue,
    });
  };
}

class WbgAddToCartRequestOptions {
  method = "POST";
  credentials = "same-origin";
  headers = {
    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
    "Cache-Control": "no-cache",
  };

  constructor(prodId, catId) {
    this.prodId = prodId;
    this.catId = catId;

    this.init();
  }

  init = () => {
    this.requestData = new WbgAddToCartRequestData(
      this.prodId,
      this.catId
    ).getPostData();
  };

  get = () => {
    return {
      method: this.method,
      credentials: this.credentials,
      headers: this.headers,
      body: this.requestData,
    };
  };
}
