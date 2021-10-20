/** Managing Login Page */
(function (webigoHelper, d) {
  const _dom = webigoHelper?.domManager;
  const _event = webigoHelper?.eventManager;

  const loginOptions = _dom.el(
    ".woocommerce-account .wbg-form-login-options-wrapper"
  );

  const loginInputs = _dom.el(".woocommerce-account input[id='username']");
  const passwordInputs = _dom.el(".woocommerce-account input[id='password']");

  const loginArea = _dom.el(".woocommerce-account #customer_login .col-1");
  const registrationArea = _dom.el(
    ".woocommerce-account #customer_login .col-2"
  );

  const showLoginFormButton = _dom.el(
    ".woocommerce-account .wbg-show-login-form"
  );
  const showRegisterFormButton = _dom.el(
    ".woocommerce-account .wbg-show-register-form"
  );

  init();

  function init() {
    const userAction = getUserAction();

    _event.bulkAttachEvent({
      el: loginInputs,
      ev: _event.type.change,
      cb: purgeBlankChar,
    });

    _event.bulkAttachEvent({
      el: passwordInputs,
      ev: _event.type.change,
      cb: purgeBlankChar,
    });

    showLoginOptions();

    if (typeof userAction !== "undefined") {
      if (userAction === "login") {
        handleLoginUser();
      }

      if (userAction === "register") {
        handleRegisterUser();
      }
    }
  }

  function getUserAction() {
    const url_string = window.location.href;
    const url = new URL(url_string);

    return url.searchParams.get("action");
  }

  function purgeBlankChar() {
    if (typeof this !== "undefined") {
      this.value = this.value.replace(/ /g, "");
    }
  }

  function handleRegisterUser() {
    if (showRegisterFormButton) {
      showRegisterFormButton.setAttribute("data-action-state", "active");
    }

    if (showLoginFormButton) {
      showLoginFormButton.setAttribute("data-action-state", "active");
    }

    hideLoginOptions();
    hideLoginArea();
    showRegistrationArea();
  }

  function handleLoginUser() {
    if (showRegisterFormButton) {
      showRegisterFormButton.setAttribute("data-action-state", "idle");
    }

    if (showLoginFormButton) {
      showLoginFormButton.setAttribute("data-action-state", "active");
    }

    hideLoginOptions();
    hideRegistrationArea();
    showLoginArea();
  }

  function hideRegistrationArea() {
    if (registrationArea) {
      _dom.hide(registrationArea);
    }
  }

  function showRegistrationArea() {
    if (registrationArea) {
      _dom.show(registrationArea);
    }
  }

  function hideLoginArea() {
    if (loginArea) {
      _dom.hide(loginArea);
    }
  }

  function showLoginArea() {
    if (loginArea) {
      _dom.show(loginArea);
    }
  }

  function hideLoginOptions() {
    if (loginOptions) {
      _dom.hide(loginOptions);
    }
  }

  function showLoginOptions() {
    if (loginOptions) {
      _dom.show(loginOptions);
    }
  }
})(webigoHelper, document);

/** Lost Password  && Addresses */
(function (webigoHelper, d) {
  const _dom = webigoHelper?.domManager;

  const newPasswordButton = _dom.el(
    ".woocommerce-lost-password button[type='submit']"
  );

  const saveAddressButton = _dom.el(
    ".woocommerce-account .woocommerce-MyAccount-content button[type='submit']"
  );

  init();

  function init() {
    styleButtons();
  }

  function styleButtons() {
    if (newPasswordButton) {
      newPasswordButton.classList.add("wbg-button", "wbg-primary-button");
    }

    if (saveAddressButton) {
      saveAddressButton.classList.add("wbg-button", "wbg-primary-button");
    }
  }
})(webigoHelper, document);

/** Fala Conosco */
(function (webigoHelper, d) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;

  const falaConoscoSection = _dom.el(".wbg-myaccount-fala-conosco-head");

  init();

  function init() {
    _event.attachEvent({
      el: falaConoscoSection,
      ev: _event.type.click,
      cb: toggleStoreContacts,
    });
  }

  function toggleStoreContacts() {
    const toggleTarget = _dom.el(
      ".wbg-myaccount-fala-conosco-container .wbg-myaccount-fala-conosco-content"
    );

    _dom.toggleVisibility(toggleTarget);
  }
})(webigoHelper, document);

/** Managing MyAccount->orders section */
(function (webigoHelper, d) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const _state = webigoHelper?.stateManager;

  const customerOrders = _dom.el(".woocommerce-account .wbg-order-head");

  init();

  function init() {
    _event.bulkAttachEvent({
      el: customerOrders,
      ev: _event.type.click,
      cb: handleShowOrderDetails,
    });
  }

  function handleShowOrderDetails() {
    const orderId = this.getAttribute("data-order-id");

    _state.setState({
      currentTarget: { orderId },
    });

    toggleOrderDetails();
  }

  function toggleOrderDetails() {
    const { orderId } = _state.state.currentTarget;

    const orderDetail = _dom.el(
      '.woocommerce-account .wbg-order-head[data-order-id="' +
        orderId +
        '"] + .wbg-order-content'
    );

    _dom.toggleVisibility(orderDetail);
  }
})(webigoHelper, document);

/** Redirect After Login */
(function (webigoHelper, d) {
  const _dom = webigoHelper?.domManager;
  const _session = webigoHelper?.sessionManager;

  const PLUGIN_REFERER_GET_PARAM = "wbg-referer";
  const wooAccountPage = _dom.el(".woocommerce-account");

  init();

  function init() {
    if (typeof wooAccountPage === "undefined") {
      return;
    }

    updateRefererInputValue();
  }

  function getPluginRefererParam() {
    const url_string = window.location.href;
    const url = new URL(url_string);

    return url.searchParams.get(PLUGIN_REFERER_GET_PARAM);
  }

  function updateRefererInputValue() {
    const wpRefererField = _dom.el(
      ".woocommerce-form-login input[name='_wp_http_referer']"
    );

    const wbgRefererParam = getPluginRefererParam();

    if (
      typeof wpRefererField !== "undefined" &&
      typeof wbgRefererParam !== "undefined"
    ) {
      wpRefererField.value = wbgRefererParam;
    }
  }
})(webigoHelper, document);
