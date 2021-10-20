(function (webigoHelper, d, $) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;

  const formExistingAccount = _dom.el(
    ".woocommerce-checkout .woocommerce-form-login"
  );
  const rememberMeLoginNode = _dom.el(
    ".woocommerce-checkout .woocommerce-form-login .woocommerce-form-login__rememberme"
  );

  const rememberMeLogin = _dom.el(
    '.woocommerce-checkout .woocommerce-form-login .woocommerce-form-login__rememberme input[name="rememberme"]'
  );

  if (rememberMeLogin) {
    rememberMeLogin.checked = true;
  }

  if (rememberMeLoginNode) {
    rememberMeLoginNode.style.display = "none";
  }

  const loginSubmitButton = _dom.el(
    ".woocommerce-checkout .woocommerce-form-login .woocommerce-form-login__submit"
  );

  if (loginSubmitButton) {
    loginSubmitButton.classList.add("wbg-button", "wbg-primary-button");
  }

  /** End Checkout Existing Account */
})(webigoHelper, document, jQuery);
