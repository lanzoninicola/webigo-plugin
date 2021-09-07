(function (webigoHelper) {
  //   if (typeof wc_add_to_cart_params === "undefined") {
  //     console.error("wc_add_to_cart_params from Woocommerce is not available");
  //   }

  if (typeof webigoHelper === "undefined") {
    console.error("Issues with the javascript of core module");
  }

  const d = document;

  const setState = webigoHelper?.stateManager?.setState;
  const state = { ...webigoHelper?.stateManager?.state };
  const eventManager = webigoHelper?.eventManager;

  const plusQtyButtons = d.getElementsByClassName("add-to-cart-plus-qty");
  const minusQtyButtons = d.getElementsByClassName("add-to-cart-minus-qty");

  const defaultParams = {
    addQtyFraction: 1,
    decreasQtyFraction: 1,
  };

  if (plusQtyButtons) {
    Object.keys(plusQtyButtons).forEach((plusButton) => {
      plusQtyButtons[plusButton].addEventListener("click", increaseQtyToCart);
    });
  }

  if (minusQtyButtons) {
    Object.keys(minusQtyButtons).forEach((minusButton) => {
      minusQtyButtons[minusButton].addEventListener("click", decreaseQtyToCart);
    });
  }

  function getProductData(eobj) {
    return {
      prodId: eobj.getAttribute("data-product-id"),
      productPrice: eobj.getAttribute("data-product-price"),
      catId: eobj.getAttribute("data-category-id"),
    };
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
    const { prodId, catId, productPrice } = getProductData(this);

    const prevQty = getPrevQuantityState(prodId, catId);

    let newQty = prevQty + defaultParams["addQtyFraction"];

    if (newQty > 0) {
      eventManager.trigger({
        eventName: "showAddToCartContainer",
        targetQuery:
          ".webigo-add-to-cart-container[data-product-id='" +
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
    const { prodId, catId, productPrice } = getProductData(this);

    const prevQty = getPrevQuantityState(prodId, catId);

    let newQty = prevQty - defaultParams["decreasQtyFraction"];

    if (newQty <= 0) {
      // TODO: disabling minus button
      newQty = 0;

      eventManager.trigger({
        eventName: "hideAddToCartContainer",
        targetQuery:
          ".webigo-add-to-cart-container[data-product-id='" +
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
        showAddToCartContainer: true,
      },
    });

    calculateSubTotal(prodId, catId);

    writeQuantityValue(prodId, catId);
  }

  function calculateSubTotal(prodId, catId) {
    const subtotalNode = d.querySelectorAll(
      ".webigo-product-cart-subtotal-value[data-product-id='" +
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
        "'].webigo-product-input-quantity"
    )[0];

    const userQty = state["prodId_" + prodId + "_catId_" + catId]?.userQty;

    if (qtyProductInputQuantityArea) {
      qtyProductInputQuantityArea.value = userQty;
    }
  }
})(webigoHelper);
