class StateManager {
  state = {};

  setState = (prevState = {}, newState = {}, options = { cb: null }) => {
    let _newState = { ...prevState };
    const { cb } = options;

    // manage upto 2 levels of state object
    Object.keys(newState).forEach((stateItem) => {
      if (_newState[stateItem.toString()]) {
        Object.keys(newState[stateItem.toString()]).forEach((subStateItem) => {
          _newState[stateItem.toString()][subStateItem.toString()] =
            newState[stateItem.toString()][subStateItem.toString()];
        });
      } else {
        _newState = {
          ..._newState,
          [stateItem.toString()]: newState[stateItem.toString()],
        };
      }
    });

    this.state = { ...prevState };

    if (typeof cb !== "undefined" && cb !== null) {
      cb();
    }
  };
}

class EventManager {
  typeManager = null;
  type = {
    click: "click",
    input: "input",
  };
  eventCollection = {};

  constructor(typeManager) {
    this.typeManager = typeManager;
  }

  listen = ({ ev, targetQuery = null, cb }) => {
    if (typeof ev === "undefined" && ev === null) {
      throw "EventManager.listen: eventName parameter is required";
    }

    if (!this.typeManager.istypeString(ev)) {
      throw "EventManager.listen: eventName must be a string";
    }

    this.eventCollection[ev.toString()] = {
      cbFn: (args) => cb(args),
      targets: [],
      data: {},
    };

    if (typeof targetQuery !== "undefined" && targetQuery !== null) {
      this.defineTarget({ ev, targetQuery });
    }
  };

  trigger = ({ ev, targetQuery = null, data }) => {
    if (!this.typeManager.istypeString(ev)) {
      throw "EventManager.trigger: eventName must be a string";
    }

    if (!this.typeManager.istypeString(targetQuery)) {
      throw "EventManager.trigger: targetQuery must be a string";
    }

    const cbFn = this.eventCollection?.[ev.toString()]?.["cbFn"];

    if (typeof targetQuery !== "undefined" && targetQuery !== null) {
      this.defineTarget({ ev, targetQuery });
    }

    const targets = this.eventCollection?.[ev.toString()]?.targets;

    targets?.forEach((target) => cbFn(target));
  };

  defineTarget = ({ ev, targetQuery }) => {
    if (!this.typeManager.istypeString(ev)) {
      throw "EventManager.defineTarget: eventName must be a string";
    }

    if (typeof targetQuery !== "undefined" && targetQuery !== null) {
      if (!this.typeManager.istypeString(targetQuery)) {
        throw "EventManager.defineTarget: targetQuery must be a string";
      }
    }

    const elementsCollection = document.querySelectorAll(targetQuery);

    elementsCollection?.forEach((el) => {
      this.eventCollection?.[ev.toString()]?.targets?.push(el);
    });
  };

  bulkAttachEvent = ({ elements, el, ev, cb }) => {
    const _el = elements || el; // manage retroactive calls

    if (!_el) {
      return;
    }

    Object.keys(_el).forEach((idx) => {
      _el[parseInt(idx, 10)].addEventListener(ev, cb);
    });
  };

  attachEvent = ({ el, ev, cb }) => {
    if (!el) {
      return;
    }

    el.addEventListener(ev, cb);
  };
}

class SessionManager {
  typeManager = null;

  constructor(typeManager) {
    this.typeManager = typeManager;
  }

  set = (key, value) => {
    if (this.typeManager.isUndefined(key) || this.typeManager.isNull(key)) {
      throw "Error to set browser sesssion. The key parameter is undefined";
    }

    if (this.typeManager.isUndefined(key) || this.typeManager.isNull(key)) {
      throw "Error to set browser sesssion. The value parameter is undefined";
    }

    let _value = value;

    if (typeof _value === "object") {
      _value = JSON.stringify(_value);
    }

    sessionStorage.setItem(key, _value);
  };
  get = (key) => {
    if (this.typeManager.isUndefined(key) || this.typeManager.isNull(key)) {
      throw "Error to get the browser sesssion for the key selected. The key parameter is undefined";
    }

    sessionStorage.getItem(key);
  };
  remove = (key) => {
    if (this.typeManager.isUndefined(key) || this.typeManager.isNull(key)) {
      throw "Error to remove from the browser sesssion the key selected. The key parameter is undefined";
    }

    sessionStorage.removeItem(key);
  };
}

class CookieManager {
  typeManager = null;

  constructor(typeManager) {
    this.typeManager = typeManager;
  }

  getCookie = (cname) => {
    let name = cname + "=";
    let ca = document.cookie.split(";");
    for (let i = 0; i < ca.length; i++) {
      let c = ca[parseInt(i, 10)];
      while (c.charAt(0) === " ") {
        c = c.substring(1);
      }
      if (c.indexOf(name) === 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  };
}

class TypeManager {
  istypeString = (value) => {
    return typeof value === "string";
  };

  istypeArray = (value) => {
    return Array.isArray(value);
  };

  isTypeObject = (value) => {
    if (Array.isArray(value)) {
      return false;
    }

    if (value === "function") {
      return false;
    }

    if (typeof value !== "object") {
      return false;
    }

    return true;
  };

  isUndefined = (value) => {
    if (typeof value === "undefined") {
      return true;
    }
  };

  isNull = (value) => {
    if (value === null) {
      return true;
    }
  };
}

class DomManager {
  domAttributes = {
    productId: "data-product-id",
    productPrice: "data-product-price",
    categoryId: "data-category-id",
    dataVisibility: "data-visibility",
  };

  visibilityState = {
    visible: "visible",
    hidden: "hidden",
    clamped: "clamped",
  };

  el = (query) => {
    const el = document.querySelectorAll(query);

    if (el) {
      if (el.length === 1) {
        return el[0];
      }
      return el;
    }
  };

  getElementAttribute = (el) => {
    return {
      prodId: el?.getAttribute(this.domAttributes.productId),
      productPrice: el?.getAttribute(this.domAttributes.productPrice),
      catId: el?.getAttribute(this.domAttributes.categoryId),
      visibility: el?.getAttribute(this.domAttributes.dataVisibility),
    };
  };

  appendChild = ({ child = null, parent = null }) => {
    if (child && parent) {
      parent.appendChild(child);
    }
  };

  insertAfter = ({ target = null, newParent = null }) => {
    if (newParent) {
      newParent.parentNode.insertBefore(target, newParent.nextSibling);
    }
  };

  show = (el) => {
    if (!el) {
      return;
    }

    el.setAttribute(
      this.domAttributes.dataVisibility,
      this.visibilityState.visible
    );
  };

  hide = (el) => {
    if (!el) {
      return;
    }

    el.setAttribute(
      this.domAttributes.dataVisibility,
      this.visibilityState.hidden
    );
  };

  clamp = (el) => {
    if (!el) {
      return;
    }

    el.setAttribute(
      this.domAttributes.dataVisibility,
      this.visibilityState.clamped
    );
  };

  shouldVisible = (el) => {
    const { visibility } = this.getElementAttribute(el);
    return visibility === this.visibilityState.visible ? true : false;
  };

  shouldHidden = (el) => {
    const { visibility } = this.getElementAttribute(el);
    return visibility === this.visibilityState.hidden ? true : false;
  };

  shouldClamped = (el) => {
    const { visibility } = this.getElementAttribute(el);
    return visibility === this.visibilityState.clamped ? true : false;
  };
}

class HttpRequestOptions {
  method = "POST";
  credentials = "same-origin";
  headers = {
    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
    "Cache-Control": "no-cache",
  };
  //RequestData object
  body = {};

  addRequestData = (HttpRequestData) => {
    if (HttpRequestData) {
      this.body = HttpRequestData.data;
    }
  };

  get = () => {
    return {
      method: this.method,
      credentials: this.credentials,
      headers: this.headers,
      body: this.body,
    };
  };
}

class HttpRequestData {
  data = {};

  set = (userRequestData) => {
    this.data = new URLSearchParams({
      ...userRequestData,
    });
    return this;
  };
}

const webigoHelper = {
  typeManager: new TypeManager(),
  stateManager: new StateManager(),
  eventManager: new EventManager(new TypeManager()),
  sessionManager: new SessionManager(new TypeManager()),
  cookieManager: new CookieManager(new TypeManager()),
  domManager: new DomManager(),
  httpRequestManager: {
    options: new HttpRequestOptions(),
    data: new HttpRequestData(),
  },
};

(function (webigoHelper, d) {
  const _event = webigoHelper?.eventManager;

  const buttons = document.querySelectorAll(".wbg-button");

  _event.bulkAttachEvent({
    el: buttons,
    ev: _event.type.click,
    cb: animateButtons,
  });

  function animateButtons(e) {
    let x = e.clientX - e.target.offsetLeft;
    let y = e.clientY - e.target.offsetTop;

    let ripples = d.createElement("span");
    ripples.setAttribute("class", "btn-animate");
    ripples.style.left = x + "px";
    ripples.style.top = y + "px";
    this.insertBefore(ripples, this.firstChild);

    setTimeout(() => {
      ripples.remove();
    }, 700);
  }
})(webigoHelper, document);
