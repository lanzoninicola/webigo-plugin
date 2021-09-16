(function (webigoHelper, d, $) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;

  const wooFormLogin = _dom.el(".woocommerce-checkout .woocommerce-form-login");

  const wooAccountFields = _dom.el(
    ".woocommerce-checkout .woocommerce-account-fields"
  );

  /**  Start Checkout Create Account  */

  const wooFormCreateAccountToggle = createNewAccountToggle();
  _dom.insertAfter({
    target: wooFormCreateAccountToggle,
    newParent: wooFormLogin,
  });

  _dom.insertAfter({
    target: wooAccountFields,
    newParent: wooFormCreateAccountToggle,
  });

  const wooFormCreateAccountToggleContent = createNewAccountToggleContent();
  _dom.appendChild({
    child: wooFormCreateAccountToggleContent,
    parent: wooFormCreateAccountToggle,
  });

  const arrowRightCreateAccount = d.createElement("i");
  arrowRightCreateAccount.classList.add("ion-md-arrow-round-forward");

  _dom.appendChild({
    child: arrowRightCreateAccount,
    parent: wooFormCreateAccountToggleContent,
  });

  _dom.hide(wooAccountFields);

  _event.attachEvent({
    el: arrowRightCreateAccount,
    ev: _event.type.click,
    cb: showFormCreateAccount,
  });

  function showFormCreateAccount() {
    if (_dom.shouldVisible(wooAccountFields)) {
      _dom.hide(wooAccountFields);
      return;
    }

    if (_dom.shouldHidden(wooAccountFields)) {
      _dom.show(wooAccountFields);
      return;
    }
  }

  /**  End Checkout Create Account  */

  /** Start Checkout Login */

  $(".woocommerce-info a").replaceWith(
    "<a class='ion-md-arrow-round-forward showlogin'></a>"
  );

  /** End Checkout Login */

  function createNewAccountToggle() {
    const el = d.createElement("div");
    el.classList.add("woocommerce-form-create-account-toggle");

    return el;
  }

  function createNewAccountToggleContent() {
    const el = d.createElement("div");
    el.innerHTML = "Não estou cadastrado";
    el.classList.add("woocommerce-info");

    return el;
  }
})(webigoHelper, document, jQuery);
