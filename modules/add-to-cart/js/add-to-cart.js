(function (webigoHelper) {
  if (typeof webigoHelper === "undefined") {
    console.error("Issues with the javascript of core module");
  }

  const d = document;
  const eventManager = webigoHelper?.eventManager;

  const addToCartButtons = d.querySelectorAll(".webigo-add-to-cart-button");

  if (addToCartButtons) {
    Object.keys(addToCartButtons).forEach((item) => {
      addToCartButtons[item].addEventListener("click", sendToCart);
    });
  }

  eventManager.addEvent({
    eventName: "showAddToCartContainer",
    callback: showAddToCartContainer,
  });

  function showAddToCartContainer(el) {
    el.setAttribute("data-visibility", "visible");
  }

  function sendToCart(e) {
    e.preventDefault();
    const productId = this.getAttribute("data-product-id");
    const productQtyNode = d.getElementById(
      "add-to-cart-quantity-input-" + productId
    );
    const wpnonceNode = d.querySelectorAll(
      '.webigo-add-to-cart-container[data-product-id="' +
        productId +
        '"] input[name="webigo_woo_add_to_cart_nonce"]'
    )[0];
    const wpnonce = wpnonceNode.getAttribute("value");

    if (productQtyNode) {
      productQty = productQtyNode.value;
    }

    if (productQty > 0) {
      const data = new URLSearchParams({
        action: "webigo_ajax_add_to_cart",
        product_id: productId,
        product_sku: "",
        quantity: productQty,
        nonce: wpnonce,
      });

      fetch(wc_add_to_cart_params.ajax_url, {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
          "Cache-Control": "no-cache",
        },
        body: data,
      })
        .then((res) => {
          // setLoadingState({ status: "success" });
          return res.json();
        })
        .then((res) => {
          // setCookie("cepChecked", "yes", 90);
          console.log(res);

          // TODO: managing error in response of wp_ajax call
          const resError = res["error"];
          if (resError) {
            console.log("some error occured");
            return;
          }

          showTopNotification();

          // showAddedToCartNotification(product_id);

          itemsInCartCheck();

          setTimeout(hideNotification, 1500);

          const addToCartProductButton = getAddToCartButtonElement(productId);
          addToCartProductButton.classList.remove("pulse-animation");
        })
        .catch((err) => {
          // setLoadingState({ status: "error" });
          console.log("error", err);
        });
    }
  }
})(webigoHelper);

class WebigoAddToCart {
  showBottomBar = () => {
    // this.setState(
    //   this.state,
    //   { containerElementVisible: true },
    //   { context: this }
    // );

    this.containerElement.setAttribute("data-visibility", "visible");
  };

  hideBottomBar = () => {};
}
