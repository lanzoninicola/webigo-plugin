(function (webigoHelper, d) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const _request = webigoHelper?.httpRequestManager;
  const _session = webigoHelper?.sessionManager;

  const shippingOptionsContainer = d.querySelectorAll(
    ".wbg-shipping-options-container"
  )[0];

  const inlineHomeNotification = d.querySelectorAll(
    ".wbg-shipping-inline-notifications"
  )[0];

  const shippingOptionDeliveryBtn = d.querySelectorAll(
    ".wbg-shipping-option.delivery"
  )[0];
  const shippingOptionGotostoreBtn = d.querySelectorAll(
    ".wbg-shipping-option.pickupstore"
  )[0];

  const cepVerificationContainer = d.querySelectorAll(
    ".wbg-cep-verification-container"
  )[0];
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
    resetShippingMethodSession();

    const userAction = getUserAction();

    initCepForm();

    if (typeof userAction !== "undefined" || userAction !== null) {
      if (userAction === "delivery") {
        handleGotoCepVerification();
      }
    }
  }

  function getUserAction() {
    const urlString = window.location.href;
    const url = new URL(urlString);

    return url.searchParams.get("shipping_method");
  }

  function initCepForm() {
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

  function resetShippingMethodSession() {
    if (!shippingOptionsContainer) {
      return;
    }

    if (
      _dom.shouldVisible(shippingOptionsContainer)
      // shippingOptionsContainer.getAttribute("data-visibility") === "visible"
    ) {
      _session.set("wbg-shipping-method", {});
    }
  }

  function handleGotoCepVerification() {
    _dom.show(inlineHomeNotification);
    gotoCepVerificationForm();
    setTimeout(() => resetAfterRedirect(), 1500);
  }

  function gotoCepVerificationForm() {
    _dom.hide(shippingOptionsContainer);
    _dom.show(cepVerificationContainer);
    _dom.show(cepFormContainer);

    if (shippingOptionDeliveryBtn) {
      const { actionState } = _dom.getElementAttribute(
        shippingOptionDeliveryBtn
      );

      if (actionState === "idle") {
        shippingOptionDeliveryBtn.setAttribute("data-action-state", "selected");
      }
    }
  }

  function resetAfterRedirect() {
    _dom.hide(inlineHomeNotification);

    if (shippingOptionGotostoreBtn) {
      shippingOptionGotostoreBtn.setAttribute("data-action-state", "idle");
    }

    if (shippingOptionDeliveryBtn) {
      shippingOptionDeliveryBtn.setAttribute("data-action-state", "idle");
    }
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

      _session.set("wbg-shipping-method", { value: "delivery" });
    }

    if (wcResponse.success === false) {
      _dom.hide(cepFormContainer);
      _dom.show(cepVerificationFailedMessage);

      _session.set("wbg-shipping-method", { value: "pickupstore" });
    }

    function handleRequestInit() {
      cepFormVerifyBtn.setAttribute("data-action-state", "pending");
    }

    function handleHttpResponseFailed() {
      _dom.show(cepFormNotificationFailed);
    }
  }

  function goBackCepForm() {
    _dom.hide(cepVerificationContainer);
    _dom.hide(cepFormContainer);
    _dom.show(shippingOptionsContainer);
  }
})(webigoHelper, document);

/** Managing Shipping on Checkout */
(function (webigoHelper, d) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const _session = webigoHelper?.sessionManager;

  const isWooCheckoutPage =
    d.body.classList.contains("woocommerce-checkout") &&
    !d.body.classList.contains("woocommerce-order-received");
  const postcodeInput = _dom.el("input[name='billing_postcode']");
  const cepInput = _dom.el("input[name='wbg-input-cep']");

  init();

  function init() {
    _event.bulkAttachEvent({
      el: cepInput,
      ev: _event.type.change,
      cb: handleCepInputChange,
    });

    if (cepInput && cepInput.value !== "") {
      _session.set("wbg-shipping-cep", cepInput.value);
    }

    updateCheckoutCepField();
  }

  function handleCepInputChange() {
    _session.set("wbg-shipping-cep", this.value);
  }

  function updateCheckoutCepField() {
    if (isWooCheckoutPage && _dom.exists(postcodeInput)) {
      const cepInputSession = _session.get("wbg-shipping-cep");
      if (cepInputSession) {
        postcodeInput.value = cepInputSession;
      }
    }
  }
})(webigoHelper, document);
