/* global webigoHelper */
/* jshint latedef:nofunc */
(function (webigoHelper, d, $, wc_cart_fragments_params) {
  const _state = webigoHelper?.stateManager;
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const _session = webigoHelper?.sessionManager;
  const _request = webigoHelper?.httpRequestManager;

  init();

  function init() {
    const addToCartButtons = d.querySelectorAll(".wbg-add-to-cart-button");
    const bulkAddToCartButtons = d.querySelectorAll(".wbg-bulk-add-to-cart");

    _event.bulkAttachEvent({
      elements: addToCartButtons,
      ev: _event.type.click,
      cb: addToCart,
    });

    _event.bulkAttachEvent({
      elements: bulkAddToCartButtons,
      ev: _event.type.click,
      cb: bulkAddToCart,
    });

    _event.listen({
      ev: "showAddToCartContainer",
      cb: showAddToCartContainer,
    });

    _event.listen({
      ev: "hideAddToCartContainer",
      cb: hideAddToCartContainer,
    });

    _event.listen({
      ev: "showAddToCartFooter",
      cb: showAddToCartFooter,
    });

    _event.listen({
      ev: "hideAddToCartFooter",
      cb: hideAddToCartFooter,
    });

    _event.listen({
      ev: "addMiniature",
      cb: addMiniature,
    });

    _event.listen({
      ev: "removeMiniature",
      cb: removeMiniature,
    });

    _event.listen({
      ev: "addedToCart",
      cb: [resetCartState, resetMiniature],
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

  async function bulkAddToCart(e) {
    e.preventDefault();
    const addToCartNotification = new WbgAddToCartNotification();
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
      addToCartNotification.hide();
    }

    function handleResponseFailed() {
      addToCartActions.idle();
      addToCartNotification.showFailed();
    }

    function handleResponseSuccess() {
      addToCartActions.completed();
      addToCartNotification.showSuccess();

      setTimeout(function () {
        _event.trigger({
          ev: "hideAddToCartFooter",
          targetQuery: ".wbg-add-to-cart-footer",
        });
        _event.trigger({ ev: "resetProductQuantity" });
        resetCartState();
        resetMiniature();
        addToCartNotification.hide();
      }, 700);
    }

    function resetCartState() {
      _state.resetItemState("cart");
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
  }

  function hideAddToCartFooter({ domTarget }) {
    _dom.hide(domTarget);
  }

  function getMiniature() {
    const _miniature = d.createElement("li");
    _miniature.classList.add("fas", "fa-beer");
    _miniature.setAttribute("data-visibility", "visible");

    return _miniature;
  }

  function addMiniature() {
    const miniatureArea = _dom.el(
      ".wbg-add-to-cart-footer .wbg-add-to-cart-miniature"
    );
    const miniature = getMiniature();

    const _miniaturesNrs = _state.state?.miniatures;

    if (_miniaturesNrs) {
      for (let index = 0; index < _miniaturesNrs; index++) {
        miniatureArea.appendChild(miniature);
      }
    }
  }

  function removeMiniature() {
    const miniatureArea = _dom.el(
      ".wbg-add-to-cart-footer .wbg-add-to-cart-miniature"
    );

    miniatureArea.removeChild(miniatureArea.lastChild);
  }

  function resetMiniature() {
    const miniatureArea = _dom.el(
      ".wbg-add-to-cart-footer .wbg-add-to-cart-miniature"
    );

    if (miniatureArea) {
      while (miniatureArea.firstChild) {
        miniatureArea.removeChild(miniatureArea.lastChild);
      }
    }

    _state.resetItemState("miniatures");
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
