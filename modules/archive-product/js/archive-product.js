/* global webigoHelper */
/* jshint latedef:nofunc */

(function (webigoHelper, d) {
  if (typeof webigoHelper === "undefined") {
    console.error("Issues with the javascript of core module");
  }

  const setState = webigoHelper?.stateManager?.setState;
  const state = { ...webigoHelper?.stateManager?.state };
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;

  const defaultParams = {
    addQtyFraction: 1,
    decreasQtyFraction: 1,
  };

  init();

  function init() {
    const plusQtyButtons = d.getElementsByClassName("btn-quantity-plus");
    const minusQtyButtons = d.getElementsByClassName("btn-quantity-minus");

    _dom.bulkAttachEvent({
      elements: plusQtyButtons,
      ev: _dom.events.click,
      cb: increaseQtyToCart,
    });

    _dom.bulkAttachEvent({
      elements: minusQtyButtons,
      ev: _dom.events.click,
      cb: decreaseQtyToCart,
    });
  }

  function getPrevQuantityState(prodId, catId) {
    const initQtyCartState = 0;

    const qtyProductState = state["prodId_" + prodId + "_catId_" + catId];

    if (typeof qtyProductState === "undefined") {
      state["prodId_" + prodId + "_catId_" + catId] = {};
      state["prodId_" + prodId + "_catId_" + catId].userQty = initQtyCartState;
    }

    return state["prodId_" + prodId + "_catId_" + catId].userQty;
  }

  function increaseQtyToCart(e) {
    e.preventDefault();
    const { prodId, catId, productPrice } = _dom.getElementAttribute(this);

    const prevQty = getPrevQuantityState(prodId, catId);

    let newQty = prevQty + defaultParams["addQtyFraction"];

    if (newQty > 0) {
      _event.trigger({
        ev: "showAddToCartContainer",
        targetQuery:
          ".wbg-add-to-cart[data-product-id='" +
          prodId +
          "'][data-category-id='" +
          catId +
          "']",
      });
    }

    setState(state, {
      ["prodId_" + prodId + "_catId_" + catId]: {
        userQty: newQty,
        price: productPrice,
      },
    });

    calculateSubTotal(prodId, catId);

    writeQuantityValue(prodId, catId);
  }

  function decreaseQtyToCart(e) {
    e.preventDefault();
    const { prodId, catId, productPrice } = _dom.getElementAttribute(this);

    const prevQty = getPrevQuantityState(prodId, catId);

    let newQty = prevQty - defaultParams["decreasQtyFraction"];

    if (newQty <= 0) {
      newQty = 0;

      _event.trigger({
        ev: "hideAddToCartContainer",
        targetQuery:
          ".wbg-add-to-cart[data-product-id='" +
          prodId +
          "'][data-category-id='" +
          catId +
          "']",
      });
    }

    setState(state, {
      ["prodId_" + prodId + "_catId_" + catId]: {
        userQty: newQty,
        price: productPrice,
      },
    });

    calculateSubTotal(prodId, catId);

    writeQuantityValue(prodId, catId);
  }

  function calculateSubTotal(prodId, catId) {
    const subtotalNode = d.querySelectorAll(
      ".wbg-product-subtotal-value[data-product-id='" +
        parseInt(prodId, 10) +
        "'][data-category-id='" +
        catId +
        "']"
    )[0];

    const userQty = state["prodId_" + prodId + "_catId_" + catId]?.userQty;
    const price = state["prodId_" + prodId + "_catId_" + catId]?.price;

    if (subtotalNode) {
      const subtotal = userQty * price;
      subtotalNode.innerText = "R$" + subtotal.toFixed(2);
    }
  }

  function writeQuantityValue(prodId, catId) {
    const qtyProductInputQuantityArea = d.querySelectorAll(
      "input[data-product-id='" +
        prodId +
        "'][data-category-id='" +
        catId +
        "'].wbg-product-input-quantity"
    )[0];
    const userQty = state["prodId_" + prodId + "_catId_" + catId]?.userQty;

    if (qtyProductInputQuantityArea) {
      qtyProductInputQuantityArea.value = userQty;
    }
  }
})(webigoHelper, document);

/**
 * Categories Attributes
 */
(function (webigoHelper, d) {
  const _dom = webigoHelper?.domManager;
  const catAttributes = d.querySelectorAll(".wbg-category-attributes");

  init();

  function init() {
    _dom.bulkAttachEvent({
      elements: catAttributes,
      ev: _dom.events.click,
      cb: showHideCatAttributes,
    });
  }

  function showHideCatAttributes() {
    const { catId } = _dom.getElementAttribute(this);

    const catAttrDescription = d.querySelectorAll(
      ".wbg-category-attributes[data-category-id='" +
        catId +
        "'] .wbg-category-attributes-descriptions"
    )[0];

    if (_dom.shouldHidden(catAttrDescription)) {
      _dom.show(catAttrDescription);
      return;
    }

    if (_dom.shouldVisible(catAttrDescription)) {
      _dom.hide(catAttrDescription);
      return;
    }
  }
})(webigoHelper, document);

/**
 * Expand the product description
 */
(function (webigoHelper, d) {
  const _dom = webigoHelper?.domManager;

  const infoProdDescriptionButtons = d.querySelectorAll(
    ".wbg-product-list .wbg-product .info-description"
  );

  init();

  function init() {
    _dom.bulkAttachEvent({
      elements: infoProdDescriptionButtons,
      ev: _dom.events.click,
      cb: showHideFullProductDescription,
    });
  }

  function showHideFullProductDescription() {
    const { prodId, catId } = _dom.getElementAttribute(this);

    const productDescription = d.querySelectorAll(
      ".wbg-product-list[data-category-id='" +
        catId +
        "'] .wbg-product[data-product-id='" +
        prodId +
        "'] .wbg-product-description"
    )[0];

    if (_dom.shouldClamped(productDescription)) {
      _dom.show(productDescription);
      return;
    }

    if (_dom.shouldVisible(productDescription)) {
      _dom.clamp(productDescription);
      return;
    }
  }
})(webigoHelper, document);
