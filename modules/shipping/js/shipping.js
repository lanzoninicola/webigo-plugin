(function (webigoHelper, d) {
  const setState = webigoHelper?.stateManager?.setState;
  const state = { ...webigoHelper?.stateManager?.state };
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const _request = webigoHelper?.httpRequestManager;
  const _cookie = webigoHelper?.cookieManager;

  const shippingOptionsContainer = d.querySelectorAll(
    ".wbg-shipping-options-container"
  )[0];

  const gotoStoreBtn = d.querySelectorAll(".wbg-button-goto-store");

  const cepFormContainer = d.querySelectorAll(".wbg-cep-form-container")[0];
  const cepFormStateSelected = d.getElementById("wbg-cep-form-select-states");
  const cepFormCepInput = d.getElementById("wbg-cep-form-input-cep");
  const cepFormNonce = d.getElementById("webigo_cep_verification_nonce");
  const cepFormVerifyBtn = d.querySelectorAll(
    ".wbg-button-cep-form-verifycep"
  )[0];
  const cepFormGoBackBtn = d.querySelectorAll(".wbg-button-cep-form-voltar")[0];
  const cepFormNotificationFailed = d.querySelectorAll(
    ".wbg-cep-form-notification-failed"
  )[0];

  const cepVerificationSuccessMessage = d.querySelectorAll(
    ".wbg-cep-verification-success"
  )[0];
  const cepVerificationFailedMessage = d.querySelectorAll(
    ".wbg-cep-verification-failed"
  )[0];

  init();

  function init() {
    initShippingOptions();

    initCepForm();
  }

  function initShippingOptions() {
    const shippingOptionDeliveryBtn = d.querySelectorAll(
      ".wbg-shipping-option.delivery"
    )[0];
    const shippingOptionGotostoreBtn = d.querySelectorAll(
      ".wbg-shipping-option.retirar_na_loja"
    )[0];

    _event.attachEvent({
      el: shippingOptionDeliveryBtn,
      ev: _event.type.click,
      cb: showCepVerificationForm,
    });

    _event.attachEvent({
      el: shippingOptionGotostoreBtn,
      ev: _event.type.click,
      cb: gotoShop,
    });

    _event.bulkAttachEvent({
      el: gotoStoreBtn,
      ev: _event.type.click,
      cb: gotoShop,
    });
  }

  function initCepForm() {
    _dom.hide(cepFormVerifyBtn);

    _event.attachEvent({
      el: cepFormCepInput,
      ev: _event.type.input,
      cb: handleOnChangeInputCep,
    });

    _event.attachEvent({
      el: cepFormVerifyBtn,
      ev: _event.type.click,
      cb: verifyCep,
    });

    _event.attachEvent({
      el: cepFormGoBackBtn,
      ev: _event.type.click,
      cb: goBackCepForm,
    });
  }

  function showCepVerificationForm() {
    _dom.hide(shippingOptionsContainer);
    _dom.show(cepFormContainer);
  }

  function gotoShop() {
    const origin = window.location.origin;
    const destinationPath = "/loja";

    window.location.href = origin + "/hazbier/" + destinationPath;
  }

  function handleOnChangeInputCep() {
    if (cepFormCepInput.value > 0) {
      _dom.show(cepFormVerifyBtn);
    }

    if (cepFormCepInput.value <= 0) {
      _dom.hide(cepFormVerifyBtn);
    }
  }

  async function verifyCep() {
    const requestOptions = _request.options;
    const requestData = _request.data;

    handleRequestInit();

    const _requestData = requestData.set({
      action: "shipping_area_validation",
      nonce: cepFormNonce.value,
      resource: "cep-form",
      country: "BR",
      state: cepFormStateSelected.value,
      postcode: cepFormCepInput.value,
    });

    requestOptions.addRequestData(_requestData);

    const url = wc_add_to_cart_params.ajax_url;
    const httpResponse = await fetch(url, requestOptions.get());

    if (!httpResponse.ok) {
      handleHttpResponseFailed();
    }

    const wcResponse = await httpResponse.json();

    if (wcResponse.success === true) {
      _dom.hide(cepFormContainer);
      _dom.show(cepVerificationSuccessMessage);
    }

    if (wcResponse.success === false) {
      _dom.hide(cepFormContainer);
      _dom.show(cepVerificationFailedMessage);
    }

    function handleRequestInit() {
      cepFormVerifyBtn.setAttribute("data-action-state", "pending");
    }

    function handleHttpResponseFailed() {
      _dom.show(cepFormNotificationFailed);
    }
  }

  function goBackCepForm() {
    _dom.hide(cepFormContainer);
    _dom.show(shippingOptionsContainer);
  }
})(webigoHelper, document);
