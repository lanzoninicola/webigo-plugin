/* global webigoHelper */
/* jshint latedef:nofunc */

(function (webigoHelper, d) {
  const _state = webigoHelper?.stateManager;
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const _session = webigoHelper?.sessionManager;

  const sessionKey = "wbg-cart";

  const defaultParams = {
    increaseQtyStep: 1,
    decreasQtyStep: 1,
  };

  init();

  function init() {
    const plusQtyButtons = d.getElementsByClassName("btn-quantity-plus");
    const minusQtyButtons = d.getElementsByClassName("btn-quantity-minus");

    _event.bulkAttachEvent({
      elements: plusQtyButtons,
      ev: _event.type.click,
      cb: handleIncrementQuantity,
    });

    _event.bulkAttachEvent({
      elements: minusQtyButtons,
      ev: _event.type.click,
      cb: handleDecrementQuantity,
    });

    _event.listen({
      ev: "resetProductQuantityAndValue",
      cb: resetProductQuantityAndValue,
    });

    initCartTotalsState();

    initSession();
  }

  function initCartTotalsState() {
    _state.setState({
      cartTotals: { qty: 0, subtotal: 0 },
    });
  }

  function initSession() {
    if (!_session.get(sessionKey)) {
      _session.set(sessionKey, {});
    }
  }

  function handleIncrementQuantity() {
    const { prodId, catId, price } = _dom.getElementAttribute(this);

    _state.setState({
      currentTarget: { prodId: prodId, catId: catId, price: price },
    });

    increaseQty();
    _event.trigger({ ev: "archive-product-prod-qty-increased" });
    updateProdSubTotal();
    writeProdQuantity();

    updateSession();
  }

  function handleDecrementQuantity() {
    const { prodId, catId, price } = _dom.getElementAttribute(this);

    _state.setState({
      currentTarget: { prodId: prodId, catId: catId, price: price },
    });

    decreaseQty();
    _event.trigger({ ev: "archive-product-prod-qty-decreased" });
    updateProdSubTotal();
    writeProdQuantity();

    updateSession();
  }

  function increaseQty() {
    const { prodId, catId } = _state.state.currentTarget;
    const prevQty = getPrevQuantity();
    const prevCarTotalQty = getPrevCartTotalsQty();

    let newQty = prevQty + defaultParams["increaseQtyStep"];
    let newCartTotalQty = prevCarTotalQty + defaultParams["increaseQtyStep"];

    _state.setState({
      cartTotals: { qty: newCartTotalQty },
      cart: {
        [prodId]: {
          qty: newQty,
          catId: catId,
        },
      },
    });
  }

  function decreaseQty() {
    const { prodId, catId } = _state.state.currentTarget;
    const prevQty = getPrevQuantity();
    const prevCarTotalQty = getPrevCartTotalsQty();
    let newQty = prevQty - defaultParams["decreasQtyStep"];
    let newCartTotalQty = prevCarTotalQty - defaultParams["decreasQtyStep"];

    if (newQty <= 0) {
      newQty = 0;
    }

    if (newCartTotalQty <= 0) {
      newCartTotalQty = 0;
    }

    _state.setState({
      cartTotals: { qty: newCartTotalQty },
      cart: {
        [prodId]: {
          qty: newQty,
          catId: catId,
        },
      },
    });
  }

  function getPrevQuantity() {
    const { prodId } = _state.state.currentTarget;

    if (typeof _state.state.cart?.[prodId] === "undefined") {
      _state.setState({
        cart: {
          [prodId]: {
            qty: 0,
          },
        },
      });
    }

    return _state.state.cart[prodId]["qty"];
  }

  function getPrevCartTotalsQty() {
    const cartTotalQty = _state.state?.cartTotals?.qty;

    if (typeof cartTotalQty === "undefined") {
      _state.setState({
        cartTotals: { qty: 0 },
      });
    }

    return _state.state.cartTotals.qty;
  }

  function updateProdSubTotal() {
    const { prodId, catId, price } = _state.state.currentTarget;

    const subtotalNode = d.querySelectorAll(
      ".wbg-product-subtotal-wrapper[data-product-id='" +
        parseInt(prodId, 10) +
        "'][data-category-id='" +
        catId +
        "'] .wbg-product-subtotal-value"
    )[0];

    let subtotal = 0;

    if (subtotalNode) {
      subtotal = _state.state.cart[prodId]["qty"] * price;
      subtotalNode.innerText = subtotal.toFixed(2);
    }

    _state.setState({
      cart: {
        [prodId]: {
          subtotal: subtotal.toFixed(2),
        },
      },
    });
  }

  function writeProdQuantity() {
    const { prodId, catId } = _state.state.currentTarget;

    const inputQty = d.querySelectorAll(
      "input[data-product-id='" +
        parseInt(prodId, 10) +
        "'][data-category-id='" +
        catId +
        "'].wbg-product-input-quantity"
    )[0];

    if (inputQty) {
      inputQty.value = _state.state.cart[prodId]["qty"];
    }
  }

  function resetProductQuantityAndValue() {
    const cartState = _state.state?.cart;

    if (cartState) {
      Object.keys(cartState).forEach((prodId) => {
        const catId = cartState[prodId]["catId"];

        const prodInputQty = _dom.el(
          "input[data-product-id='" +
            parseInt(prodId, 10) +
            "'][data-category-id='" +
            catId +
            "'].wbg-product-input-quantity"
        );

        const subtotalNode = d.querySelectorAll(
          ".wbg-product-subtotal-wrapper[data-product-id='" +
            parseInt(prodId, 10) +
            "'][data-category-id='" +
            catId +
            "'] .wbg-product-subtotal-value"
        )[0];

        prodInputQty.value = 0;
        subtotalNode.innerText = 0;
      });
    }
  }

  function updateSession() {
    _session.set(sessionKey, _state.state.cart);
  }
})(webigoHelper, document);

/**
 * Categories Attributes
 */
(function (webigoHelper, d) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const catAttributes = d.querySelectorAll(".wbg-category-attributes");

  init();

  function init() {
    _event.bulkAttachEvent({
      elements: catAttributes,
      ev: _event.type.click,
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
 * Expand the category
 */
(function (webigoHelper, d, $) {
  const _state = webigoHelper?.stateManager;
  const _dom = webigoHelper?.domManager;
  const _event = webigoHelper?.eventManager;

  const toggleCatAreas = d.querySelectorAll(".product-category-toggle");
  const collapseCatButtons = d.querySelectorAll(".wbg-category-collapse");
  let actionState = "collapsed";

  init();

  function init() {
    _event.bulkAttachEvent({
      elements: toggleCatAreas,
      ev: _event.type.click,
      cb: toggle,
    });

    _event.bulkAttachEvent({
      elements: collapseCatButtons,
      ev: _event.type.click,
      cb: toggle,
    });

    _event.listen({
      ev: "minicartOpened",
      cb: collapseAllCategories,
    });
  }

  function getAnimatedTargets(catId) {
    const toggleCatArea = d.querySelectorAll(
      ".product-category-toggle[data-category-id='" + catId + "'"
    )[0];

    const toggleButton = d.querySelectorAll(
      ".arrow-toggle-expand-collapse[data-category-id='" + catId + "'"
    )[0];

    return { toggleCatArea, toggleButton };
  }

  function toggle() {
    const { catId } = _dom.getElementAttribute(this);

    $(
      ".wbg-product-list-wrapper[data-category-id=" + catId + "]"
    ).slideToggle();

    switch (actionState) {
      case "collapsed":
        expandCategories(catId);
        break;
      case "expanded":
        collapseCategories(catId);
        break;
    }
  }

  function expandCategories(catId) {
    const { toggleCatArea, toggleButton } = getAnimatedTargets(catId);
    toggleCatArea.setAttribute("data-action-state", "expanded");
    toggleButton.setAttribute("data-action-state", "expanded");
    actionState = "expanded";
  }

  function collapseCategories(catId) {
    const { toggleCatArea, toggleButton } = getAnimatedTargets(catId);
    toggleCatArea.setAttribute("data-action-state", "collapsed");
    toggleButton.setAttribute("data-action-state", "collapsed");
    actionState = "collapsed";
  }

  function collapseAllCategories() {
    const categoriesCards = d.querySelectorAll(".wbg-product-list-wrapper");

    if (categoriesCards) {
      categoriesCards.forEach((item) => (item.style.display = "none"));
    }
  }
})(webigoHelper, document, jQuery);

/**
 * Zoom Product
 */
(function (webigoHelper, d) {
  const _state = webigoHelper?.stateManager;
  const _dom = webigoHelper?.domManager;
  const _event = webigoHelper?.eventManager;

  const prodSheetButtons = d.querySelectorAll(
    ".wbg-product-list .wbg-zoom-product-sheet"
  );

  const overlay = d.querySelectorAll(".wbg-overlay")[0];

  const prodImage = d.querySelectorAll(
    ".wbg-product-list .wbg-zoom-product-image"
  );

  const closeProdZoomButtons = d.querySelectorAll(
    ".wbg-product-list .wbg-product-zoom .wbg-product-zoom-close"
  );

  init();

  function init() {
    _event.bulkAttachEvent({
      elements: prodSheetButtons,
      ev: _event.type.click,
      cb: handleShowZoomProduct,
    });

    _event.bulkAttachEvent({
      elements: prodImage,
      ev: _event.type.click,
      cb: handleShowZoomProduct,
    });

    _event.bulkAttachEvent({
      elements: closeProdZoomButtons,
      ev: _event.type.click,
      cb: hideZoomWindow,
    });

    _event.attachEvent({
      el: overlay,
      ev: _event.type.click,
      cb: hideZoomWindow,
    });
  }

  function handleShowZoomProduct() {
    const { prodId, catId } = _dom.getElementAttribute(this);

    _state.setState({
      currentZoomTarget: { prodId: prodId, catId: catId },
    });

    showProductZoomWindow();

    if (this.classList.contains("wbg-zoom-product-image")) {
      zoomProductImage();
    }

    if (this.classList.contains("wbg-zoom-product-sheet")) {
      zoomProductSheet();
    }
  }

  function showProductZoomWindow() {
    const { prodId, catId } = _state.state.currentZoomTarget;

    const zoomWindow = _dom.el(
      ".wbg-product-zoom[data-product-id='" +
        prodId +
        "'][data-category-id='" +
        catId +
        "']"
    );

    if (overlay) {
      overlay.appendChild(zoomWindow);
    }

    if (_dom.shouldHidden(overlay)) {
      _dom.show(overlay);
    }

    if (_dom.shouldHidden(zoomWindow)) {
      _dom.show(zoomWindow);
    }
  }

  function hideZoomWindow() {
    const zoomWindow = d.querySelectorAll(
      ".wbg-product-zoom[data-visibility='visible']"
    );

    const zoomImageWrapper = d.querySelectorAll(
      ".wbg-product-zoom-image-wrapper[data-visibility='visible']"
    );

    const zoomProductSheetWrapper = d.querySelectorAll(
      ".wbg-product-sheet-wrapper[data-visibility='visible']"
    );

    if (zoomWindow) {
      Object.values(zoomWindow).forEach((item) => _dom.hide(item));
    }

    if (zoomImageWrapper) {
      Object.values(zoomImageWrapper).forEach((item) => _dom.hide(item));
    }

    if (zoomProductSheetWrapper) {
      Object.values(zoomProductSheetWrapper).forEach((item) => _dom.hide(item));
    }
  }

  function zoomProductImage() {
    const { prodId, catId } = _state.state.currentZoomTarget;

    const zoomImageWrapper = _dom.el(
      ".wbg-product-zoom[data-product-id='" +
        prodId +
        "'][data-category-id='" +
        catId +
        "'] .wbg-product-zoom-image-wrapper"
    );

    if (_dom.shouldHidden(zoomImageWrapper)) {
      _dom.show(zoomImageWrapper);
    }
  }

  function zoomProductSheet() {
    const { prodId, catId } = _state.state.currentZoomTarget;

    const zoomProductSheetWrapper = _dom.el(
      ".wbg-product-zoom[data-product-id='" +
        prodId +
        "'][data-category-id='" +
        catId +
        "'] .wbg-product-sheet-wrapper"
    );

    if (_dom.shouldHidden(zoomProductSheetWrapper)) {
      _dom.show(zoomProductSheetWrapper);
    }
  }
})(webigoHelper, document);

/**
 * Back button notification
 */
// (function (webigoHelper, d) {
//   const _event = webigoHelper?.eventManager;
//   const _dom = webigoHelper?.domManager;

//   const archivePage = _dom.el(
//     ".woocommerce-shop .wbg-archive-product-container"
//   );

//   init();

//   function init() {
//     window.addEventListener("beforeunload", warnBackButton);
//   }

//   function isMinicartActive() {
//     const minicartActive = d.querySelectorAll(
//       ".mini-cart-wrapper .cart-detail.active"
//     );

//     if (minicartActive && minicartActive.length > 0) {
//       return true;
//     }

//     return false;
//   }

//   function warnBackButton(e) {
//     if (!archivePage) {
//       return;
//     }

//     if (isMinicartActive()) {
//       return;
//     }

//     e.preventDefault();
//     e.returnValue = "";
//   }
// })(webigoHelper, document);
