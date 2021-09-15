/* global webigoHelper */
/* jshint latedef:nofunc */
(function (webigoHelper, d, $, wc_cart_fragments_params) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const _session = webigoHelper?.sessionManager;

  init();

  function init() {
    const addToCartButtons = d.querySelectorAll(".wbg-add-to-cart-button");

    _event.bulkAttachEvent({
      elements: addToCartButtons,
      ev: _event.type.click,
      cb: addToCart,
    });

    _event.listen({
      ev: "showAddToCartContainer",
      cb: showAddToCartContainer,
    });

    _event.listen({
      ev: "hideAddToCartContainer",
      cb: hideAddToCartContainer,
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

      // removed the link from the product item
      const miniCartItems = d.querySelectorAll(
        ".mini-cart-wrapper .mini_cart_item a:not(.remove_from_cart_button)"
      );

      if (miniCartItems) {
        if (miniCartItems.length > 0) {
          miniCartItems.forEach((item) => {
            item.setAttribute("href", "#");
          });
        }
      }
    }
  }

  function showAddToCartContainer(el) {
    _dom.show(el);
  }

  function hideAddToCartContainer(el) {
    _dom.hide(el);
  }
})(webigoHelper, document, jQuery, wc_cart_fragments_params);

/**
 * Class to handle the notifications
 */
class WbgAddToCartNotification {
  successElement = false;
  failedElement = false;

  constructor(prodId, catId) {
    this.prodId = prodId;
    this.catId = catId;
    this._dom = webigoHelper?.domManager;

    this.init();
  }

  init = () => {
    this.getSuccessElement();
    this.getFailedElement();
  };

  getSuccessElement = () => {
    this.successElement = document.querySelectorAll(
      ".wbg-add-to-cart[data-product-id='" +
        this.prodId +
        "'][data-category-id='" +
        this.catId +
        "'] .wbg-add-to-cart-notification-success"
    )[0];
  };

  getFailedElement = () => {
    this.failedElement = document.querySelectorAll(
      ".wbg-add-to-cart[data-product-id='" +
        this.prodId +
        "'][data-category-id='" +
        this.catId +
        "'] .wbg-add-to-cart-notification-failed"
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
  element = false;
  _dom = false;

  constructor(prodId, catId) {
    this.prodId = prodId;
    this.catId = catId;
    this._dom = webigoHelper?.domManager;

    this.init();
  }

  init = () => {
    this.getElement();
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
