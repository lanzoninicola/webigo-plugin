(function (webigoHelper, d) {
  const setState = webigoHelper?.stateManager?.setState;
  const state = { ...webigoHelper?.stateManager?.state };
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;

  init();

  function init() {
    const deliveryOption = d.querySelectorAll(
      ".wbg-shipping-option.delivery"
    )[0];
    const retireLojaOption = d.querySelectorAll(
      ".wbg-shipping-option.retirar_na_loja"
    )[0];

    _event.attachEvent({
      el: deliveryOption,
      ev: _event.type.click,
      cb: showCepVerification,
    });

    _event.attachEvent({
      el: retireLojaOption,
      ev: _event.type.click,
      cb: gotoShop,
    });
  }

  function showCepVerification() {
    const shoppingOptionsContainer = d.querySelectorAll(
      ".wbg-shipping-container"
    );
  }

  function gotoShop() {}
})(webigoHelper, document);
