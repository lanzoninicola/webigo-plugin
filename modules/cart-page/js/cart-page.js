class WbgCartCoupon {
  couponNodeParent = document.querySelectorAll(
    ".woocommerce-cart .woocommerce .coupon"
  )[0];

  couponNodeChilds = document.querySelectorAll(
    ".woocommerce-cart .woocommerce .coupon > *:not(span)"
  );

  couponButton = document.querySelectorAll(
    ".woocommerce-cart .woocommerce .button[name='apply_coupon']"
  )[0];

  accordionRoot = null;

  state = {
    visible: false,
  };

  constructor({ _dom = null, _event = null }) {
    this._dom = _dom;
    this._event = _event;
  }

  show = () => {
    if (this.couponNodeChilds) {
      if (this.couponNodeChilds.length > 0) {
        this.couponNodeChilds.forEach((item) => this._dom.show(item));
        this.state.visible = true;
      }
    }
  };

  hide = () => {
    if (this.couponNodeChilds) {
      if (this.couponNodeChilds.length > 0) {
        this.couponNodeChilds.forEach((item) => this._dom.hide(item));
        this.state.visible = false;
      }
    }
  };

  toggle = () => {
    if (this.state.visible) {
      this.hide();
      this.accordionRoot.innerHTML =
        "Clique aqui para ensir o cupom de desconto";
      return;
    }

    if (this.state.visible === false) {
      this.show();
      this.accordionRoot.innerHTML = "Fechar";
      return;
    }
  };

  buildAccordion = () => {
    if (this.couponNodeParent) {
      const item = document.createElement("span");
      item.innerHTML = "Clique aqui para ensir o cupom de desconto";
      this.couponNodeParent.appendChild(item);
      this.accordionRoot = item;
    }
  };
}

(function (webigoHelper, d) {
  const _event = webigoHelper?.eventManager;
  const _dom = webigoHelper?.domManager;
  const cartCoupon = new WbgCartCoupon({ _dom, _event });

  init();

  function init() {
    cartCoupon.hide();

    cartCoupon.buildAccordion();

    _event.attachEvent({
      el: cartCoupon.accordionRoot,
      ev: _event.type.click,
      cb: toggleVisibilityCouponNode,
    });
  }

  function toggleVisibilityCouponNode() {
    cartCoupon.toggle();
  }
})(webigoHelper, document);
