(function (webigoHelper) {
  //   if (typeof wc_add_to_cart_params === "undefined") {
  //     console.error("wc_add_to_cart_params from Woocommerce is not available");
  //   }

  if (typeof webigoHelper === "undefined") {
    console.error("Issues with the javascript of core module");
  }

  const d = document;

  const setState = webigoHelper?.stateManagement?.setState;

  const plusQtyButtons = d.getElementsByClassName("add-to-cart-plus-qty");
  const minusQtyButtons = d.getElementsByClassName("add-to-cart-minus-qty");
  const webigoAddToCart = new WebigoAddToCart();
  const productQuantity = new ProductQuantity();

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

  function increaseQtyToCart() {
    const product = {
      id: this.getAttribute("data-product-id"),
      price: this.getAttribute("data-product-price"),
    };

    productQuantity.init(productId);
  }

  function decreaseQtyToCart(e) {}

  writeQuantityValue = (productId) => {
    const qtyProductInputQuantityArea = d.querySelectorAll(
      "input[data-product-id='" + productId + "'].webigo-product-input-quantity"
    )[0];

    const userQty = state["productId_" + productId]?.userQty;

    if (qtyProductInputQuantityArea) {
      qtyProductInputQuantityArea.value = userQty;
    }
  };
})(webigoHelper);

class ProductQuantity {
  product = null;
  quantity = 0;

  defaultParams = {
    addQtyFraction: 1,
    decreasQtyFraction: 1,
  };

  state = {};

  init = (product) => {
    this.product = product;
  };

  increaseQtyToCart = (e) => {
    e.preventDefault();
    const { productId, productPrice } = getProductInfo(this);

    const prevQty = getPrevQuantityState(productId);
    webigoAddToCart.init(productId);

    let newQty = prevQty + defaultParams["addQtyFraction"];

    if (newQty >= 0) {
      webigoAddToCart.showBottomBar();
      // enableAddToCartButtonOf(productId);
    } else {
      // disableAddToCartButtonOf(productId);
    }

    const prevState = { ...state };

    console.log(this);

    setState(prevState, {
      ["productId_" + productId]: {
        userQty: newQty,
        price: productPrice,
      },
    });

    calculateSubTotal(productId, productPrice);

    writeQuantityValue(productId);
  };

  decreaseQtyToCart = (e) => {
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
      },
    });

    calculateSubTotal(productId);

    writeQuantityValue(productId);
  };

  calculateSubTotal = (productId) => {
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
  };

  getPrevQuantityState = (productId) => {
    const initQtyCartState = 0;

    const qtyProductState = state["productId_" + productId];

    if (typeof qtyProductState === "undefined") {
      state["productId_" + productId] = {};
      state["productId_" + productId].userQty = initQtyCartState;
    }

    return state["productId_" + productId].userQty;
  };
}

class ArchiveProductSubtotal {}
