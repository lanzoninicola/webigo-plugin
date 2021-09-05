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

  function getProductInfo(eobj) {
    return {
      productId: eobj.getAttribute("data-product-id"),
      productPrice: eobj.getAttribute("data-product-price"),
    };
  }

  function getPrevQuantityState(productId) {
    const initQtyCartState = 0;

    const qtyProductState = state["productId_" + productId];

    if (typeof qtyProductState === "undefined") {
      state["productId_" + productId] = {};
      state["productId_" + productId].userQty = initQtyCartState;
    }

    return state["productId_" + productId].userQty;
  }

  function increaseQtyToCart(e) {
    e.preventDefault();
    const { productId, productPrice } = getProductInfo(this);

    const prevQty = getPrevQuantityState(productId);

    let newQty = prevQty + defaultParams["addQtyFraction"];

    if (newQty >= 0) {
      // enableAddToCartButtonOf(productId);
    } else {
      // disableAddToCartButtonOf(productId);
    }

    setState(state, {
      ["productId_" + productId]: {
        userQty: newQty,
        price: productPrice,
      },
    });

    eventManager.trigger({
      eventName: "showAddToCartContainer",
      targetQuery:
        ".webigo-add-to-cart-container[data-product-id='" + productId + "']",
    });

    calculateSubTotal(productId, productPrice);

    writeQuantityValue(productId);
  }

  function decreaseQtyToCart(e) {
    e.preventDefault();
    const { productId, productPrice } = getProductInfo(this);

    const prevQty = getPrevQuantityState(productId);

    let newQty = prevQty - defaultParams["decreasQtyFraction"];

    if (newQty <= 0) {
      // TODO: disabling minus button
      newQty = 0;
      // disableAddToCartButtonOf(productId);
    } else {
      // enableAddToCartButtonOf(productId);
    }

    console.log(state);

    setState(state, {
      ["productId_" + productId]: {
        userQty: newQty,
        price: productPrice,
        showAddToCartContainer: true,
      },
    });

    calculateSubTotal(productId);

    writeQuantityValue(productId);
  }

  function calculateSubTotal(productId) {
    const subtotalNodes = d.querySelectorAll(
      ".webigo-product-cart-subtotal-value[data-product-id='" +
        parseInt(productId, 10) +
        "']"
    );

    const userQty = state["productId_" + productId]?.userQty;
    const price = state["productId_" + productId]?.price;

    if (subtotalNodes.length > 0) {
      const subtotalNode = subtotalNodes[0];

      if (subtotalNode) {
        const subtotal = userQty * price;
        subtotalNode.innerText = "R$" + subtotal.toFixed(2);
      }
    }
  }

  function writeQuantityValue(productId) {
    const qtyProductInputQuantityArea = d.querySelectorAll(
      "input[data-product-id='" + productId + "'].webigo-product-input-quantity"
    )[0];

    const userQty = state["productId_" + productId]?.userQty;

    if (qtyProductInputQuantityArea) {
      qtyProductInputQuantityArea.value = userQty;
    }
  }

  function getAddToCartButtonElement(productId) {
    const addToCartProductButtons = d.querySelectorAll(
      "form.cart button[type='submit'][data-product-id='" +
        parseInt(productId, 10) +
        "'].add_to_cart_button"
    );

    if (addToCartProductButtons.length > 0) {
      return addToCartProductButtons[0];
    }
  }

  function enableAddToCartButtonOf(productId) {
    const addToCartProductButton = getAddToCartButtonElement(productId);

    showNode(addToCartProductButton);
    addToCartProductButton.classList.add("pulse-animation");

    // if (addToCartProductButton) {
    //   addToCartProductButton.removeAttribute("disabled");
    // }
  }

  function disableAddToCartButtonOf(productId) {
    const addToCartProductButton = getAddToCartButtonElement(productId);

    hideNode(addToCartProductButton);
    addToCartProductButton.classList.remove("pulse-animation");
  }
})(webigoHelper);
