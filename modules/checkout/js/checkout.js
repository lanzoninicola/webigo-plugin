(function (webigoHelper, d, $) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;

  const formExistingAccount = _dom.el(
    ".woocommerce-checkout .woocommerce-form-login"
  );

  /**  Start Checkout New Account  */

  const formNewAccount = _dom.el(
    ".woocommerce-checkout .woocommerce-account-fields"
  );

  const newAccountToggle = createNewAccountToggle();
  _dom.insertAfter({
    target: newAccountToggle,
    newParent: formExistingAccount,
  });

  _dom.insertAfter({
    target: formNewAccount,
    newParent: newAccountToggle,
  });

  const newAccountToggleContent = createNewAccountToggleContent();
  _dom.appendChild({
    child: newAccountToggleContent,
    parent: newAccountToggle,
  });

  const arrowRightNewAccount = d.createElement("a");
  arrowRightNewAccount.classList.add(
    "ion-md-arrow-round-forward",
    "shownewaccount"
  );

  _dom.appendChild({
    child: arrowRightNewAccount,
    parent: newAccountToggleContent,
  });

  const newAccountInfo = d.createElement("p");
  newAccountInfo.innerText =
    'Caso você seja um novo cliente, para finalizar sua compra e criar uma conta, preencha o formulário abaixo e depois siga para a seção de "Detalhes de faturamento"';

  formNewAccount.insertBefore(newAccountInfo, formNewAccount.firstElementChild);

  formNewAccount.style.display = "none";

  _event.attachEvent({
    el: arrowRightNewAccount,
    ev: _event.type.click,
    cb: showFormNewAccount,
  });

  function showFormNewAccount() {
    // This to clone the woo login form animation in checkout page
    $(".woocommerce-checkout .woocommerce-account-fields").slideToggle();
  }

  /**  End Checkout New Account  */

  /** Start Checkout Existing Account */
  const lostPassword = _dom.el(
    ".woocommerce-checkout .woocommerce-form-login .lost_password"
  );

  const pwdInput = _dom.el(
    '.woocommerce-checkout .woocommerce-form-login input[name="password"]'
  );

  _dom.insertAfter({
    target: lostPassword,
    newParent: pwdInput,
  });

  $(".woocommerce-form-login-toggle .woocommerce-info a").replaceWith(
    "<a class='ion-md-arrow-round-forward showlogin'></a>"
  );

  const existingAccountInfo = _dom.el(
    ".woocommerce-checkout .woocommerce-form-login > p:first-child"
  );
  existingAccountInfo.innerHTML =
    "Caso você já tenha comprado conosco antes, informe seus dados abaixo.";

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
