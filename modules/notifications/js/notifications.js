(function (webigoHelper, d) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const _request = webigoHelper?.httpRequestManager;
  const _session = webigoHelper?.sessionManager;

  const successNotification = _dom.el(
    ".wbg-notifications-container .wbg-notification-success"
  );

  const failedNotification = _dom.el(
    ".wbg-notifications-container .wbg-notification-failed"
  );

  const HIDE_NOTIFICATION_TIMER = 2000;

  init();

  function init() {
    _event.listen({ ev: "successNotification", cb: showSuccessNotification });
    _event.listen({ ev: "failedNotification", cb: showFailedNotification });

    _event.bulkAttachEvent({
      elements: successNotification,
      ev: _event.type.click,
      cb: hideNotification,
    });

    _event.bulkAttachEvent({
      elements: failedNotification,
      ev: _event.type.click,
      cb: hideNotification,
    });
  }

  function showSuccessNotification() {
    _dom.show(successNotification);

    setTimeout(function () {
      hideNotification();
    }, HIDE_NOTIFICATION_TIMER);
  }

  function showFailedNotification() {
    _dom.show(failedNotification);

    setTimeout(function () {
      hideNotification();
    }, HIDE_NOTIFICATION_TIMER);
  }

  function hideNotification() {
    _dom.hide(successNotification);
    _dom.hide(failedNotification);
  }
})(webigoHelper, document);
