(function (webigoHelper, d) {
  const setState = webigoHelper?.stateManager?.setState;
  const state = { ...webigoHelper?.stateManager?.state };
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const _request = webigoHelper?.httpRequestManager;
  const _cookie = webigoHelper?.cookieManager;

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
      cb: showCepVerification,
    });

    _event.attachEvent({
      el: shippingOptionGotostoreBtn,
      ev: _event.type.click,
      cb: gotoShop,
    });
  }

  function initCepForm() {
    const cepFormVerifyBtn = d.querySelectorAll(
      ".wbg-button-cep-form-verifycep"
    )[0];
    const cepFormGoBackBtn = d.querySelectorAll(
      ".wbg-button-cep-form-voltar"
    )[0];

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

  function showCepVerification() {
    const shoppingOptionsContainer = d.querySelectorAll(
      ".wbg-shipping-container"
    );
  }

  function gotoShop() {}

  async function verifyCep() {
    const cepFormStateSelected = d.getElementById("wbg-cep-form-select-states");
    const cepFormCepInput = d.getElementById("wbg-cep-form-input-cep");
    const cepFormNonce = d.getElementById("webigo_cep_verification_nonce");
    const requestOptions = _request.options;
    const requestData = _request.data;

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

    const wcResponse = await httpResponse.json();

    console.log(wcResponse);
  }

  function goBackCepForm() {}
})(webigoHelper, document);
