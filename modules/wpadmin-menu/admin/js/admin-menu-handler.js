class RoleSelector {
  sessionKey = "role-selected";

  constructor({ target, submitBtn, helper }) {
    this.targetQueryElement = target;
    this.submitBtnQueryElement = submitBtn;
    this._session = helper?.sessionManager;
    this._dom = helper?.domManager;
    this._event = helper?.eventManager;

    this.init();
  }

  init = () => {
    this.target = this._dom.el(this.targetQueryElement);
    this.submitBtn = this._dom.el(this.submitBtnQueryElement);
  };

  get = () => {
    return this.target.value;
  };

  getFromSession = () => {
    const sessionRoleSelected = this._session.get(this.sessionKey);

    if (sessionRoleSelected) {
      this.target.value = sessionRoleSelected;
      this.setPostData();
      return sessionRoleSelected;
    }
  };

  onChange = (cb) => {
    this._event.attachEvent({
      el: this.target,
      ev: this._event.type.change,
      cb: cb,
    });
  };

  handleChange = () => {
    this.setPostData();
    this.setSession();
    return this.target.value;
  };

  setPostData = () => {
    if (this.submitBtn) {
      this.submitBtn.value = this.target.value;
    }
  };

  setSession = () => {
    this._session.set(this.sessionKey, this.target.value);
  };

  disable = () => {
    this.target.disabled = true;
    this.submitBtn.disabled = true;
  };

  enable = () => {
    this.target.disabled = false;
    this.submitBtn.disabled = false;
  };
}

(function (webigoHelper, d) {
  const _state = webigoHelper?.stateManager;
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const _session = webigoHelper?.sessionManager;

  const roleSelector = new RoleSelector({
    target: "select[name='system-role']",
    submitBtn: "button[type='submit'][name='system-role-selected']",
    helper: webigoHelper,
  });

  const menuItemsForm = _dom.el("form[role='edit-menu-visibility']");
  const menuItems = _dom.el(
    "form[role='edit-menu-visibility'] .wbg-menu-item input[type='checkbox']"
  );
  const menuItemsSubmit = _dom.el(
    "form[role='edit-menu-visibility'] button[type='submit'][name='edit-admin-menu-settings']"
  );

  if (menuItems === false) {
    return;
  }

  init();

  function init() {
    initRoleSelected();

    roleSelector.onChange(handleRoleChanged);

    _event.bulkAttachEvent({
      el: menuItems,
      ev: _event.type.change,
      cb: handleMenuItemChange,
    });

    _event.bulkAttachEvent({
      el: menuItemsSubmit,
      ev: _event.type.change,
      cb: enableRoleSelection,
    });
  }

  function initRoleSelected() {
    _state.setState({
      currenRole: roleSelector.get(),
    });
    setRoleMenuItemsState();
  }

  function handleRoleChanged() {
    roleSelector.handleChange();

    _dom.hide(menuItemsForm);
  }

  function setRoleMenuItemsState() {
    menuItems?.forEach((item) => {
      const slug = item.getAttribute("id");
      _state.setState({
        initMenuItems: {
          [slug]: item.checked,
        },
      });
    });
  }

  function handleMenuItemChange() {
    const menuItem = this;
    const menuItemSlug = menuItem.getAttribute("id");

    _state.setState({
      updatedMenuItems: {
        [menuItemSlug]: menuItem.checked,
      },
    });

    if (shouldMenuItemsVisibilityChanged()) {
      roleSelector.disable();
    } else {
      roleSelector.enable();
    }

    const postData = {
      [_state.state.currenRole]: _state.state.updatedMenuItems,
    };

    menuItemsSubmit.value = JSON.stringify(postData);
  }

  function shouldMenuItemsVisibilityChanged() {
    let result = [];
    if (_state.state.hasOwnProperty("updatedMenuItems")) {
      Object.keys(_state.state.updatedMenuItems).forEach((item) => {
        if (
          _state.state.updatedMenuItems[item] !==
          _state.state.initMenuItems[item]
        ) {
          result.push(item);
        }
      });

      if (result.length > 0) {
        return true;
      }
    }

    return false;
  }

  function enableRoleSelection() {
    roleSelector.enable();
  }
})(webigoHelper, document);
