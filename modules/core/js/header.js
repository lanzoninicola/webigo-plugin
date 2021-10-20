/** Collapse Header On Scroll */

(function (webigoHelper, d) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;

  const header = _dom.el("header#bricks-header");
  const headerLabels = _dom.el(".wbg-header-nav-label");

  init();

  function init() {
    if (header) {
      header.setAttribute("data-action-state", "idle");
    }

    if (headerLabels) {
      headerLabels.forEach((item) =>
        item.setAttribute("data-visibility", "visible")
      );
    }

    window.onscroll = function () {
      scrollFunction();
    };
  }

  function scrollFunction() {
    if (d.body.scrollTop > 50 || d.documentElement.scrollTop > 50) {
      fixedHeader();
    } else {
      releaseHeader();
    }
  }

  function fixedHeader() {
    if (header) {
      header.setAttribute("data-action-state", "fixed");
    }
  }

  function releaseHeader() {
    if (header) {
      header.setAttribute("data-action-state", "idle");
    }
  }
})(webigoHelper, document);
