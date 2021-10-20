/** Overlay */
(function (webigoHelper, d) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;

  const overlay = _dom.el(".wbg-overlay");

  init();

  function init() {
    if (overlay) {
      overlay.addEventListener("click", toggleOverlay);
    }
  }

  function toggleOverlay() {
    const { visibility } = _dom.getElementAttribute(overlay);

    if (visibility === "hidden") {
      _dom.show(overlay);
    }

    if (visibility === "visible") {
      _dom.hide(overlay);
    }
  }
})(webigoHelper, document);
