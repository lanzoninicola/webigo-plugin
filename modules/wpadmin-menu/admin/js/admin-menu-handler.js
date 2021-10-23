(function (webigoHelper, d) {
  const _session = webigoHelper.SessionManager;
  const _event = webigoHelper.EventManager;

  const roleSelector = _dom.el("select[name='system-role-selected']");

  init();

  function init() {
    _event.attachEvent({
      el: roleSelector,
      ev: _event.type.change,
      cb: getSelection,
    });
  }

  function getSelection() {
    const roleSelector = this;

    console.log(this.value);
  }
})(webigoHelper, document);
