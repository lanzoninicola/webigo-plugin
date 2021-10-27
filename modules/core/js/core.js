class StateManager {
  state = {};

  setState = (newState = {}, options = { cb: null }) => {
    const { cb } = options;

    const _prevState = { ...this.state };
    const _prevStateMergedKeys = this.mergeKeys(newState, _prevState);
    const _newState = this.mergeData(newState, _prevStateMergedKeys);

    this.state = { ..._newState };
    if (typeof cb !== "undefined" && cb !== null) {
      cb();
    }
  };

  resetState = () => {
    this.state = {};
  };

  resetItemState = (item) => {
    if (typeof item === "undefined") {
      throw "resetItemState expects a parameter";
    }

    if (typeof item !== "string") {
      throw "resetItemState parameter must be a string";
    }

    if (this.state.hasOwnProperty(item)) {
      if (typeof this.state[item] === "object") {
        this.state[item] = {};
      }

      if (typeof this.state[item] === "string") {
        this.state[item] = "";
      }

      if (typeof this.state[item] === "number") {
        this.state[item] = 0;
      }
    }
  };

  mergeKeys = (source = {}, target = {}) => {
    for (const [key, val] of Object.entries(source)) {
      if (typeof source === "object") {
        const isKeyExists = key in target;
        if (!isKeyExists) {
          target[key] = {};
        }
        this.mergeKeys(source[key], target[key]);
      }
    }

    return target;
  };

  mergeData = (source = {}, target = {}) => {
    for (const [key, val] of Object.entries(source)) {
      if (val !== null && typeof val === `object`) {
        if (typeof target[key] === "undefined") {
          target[key] = new val.__proto__.constructor();
        }
        this.mergeData(val, target[key]);
      } else {
        target[key] = val;
      }
    }
    return target;
  };
}

class EventManager {
  typeManager = null;
  type = {
    click: "click",
    input: "input",
    change: "input",
  };
  eventCollection = {};

  constructor(typeManager, domManager) {
    this.typeManager = typeManager;
    this._dom = domManager;
  }

  initEventObject = ({ ev }) => {
    if (typeof ev === "undefined" && ev === null) {
      throw "EventManager.listen: eventName parameter is required";
    }

    if (!this.typeManager.istypeString(ev)) {
      throw "EventManager.listen: eventName must be a string";
    }

    this.eventCollection[ev.toString()] = {
      cb: false,
      targets: [],
      payload: {},
    };
  };

  listen = ({ ev, cb }) => {
    this.initEventObject({ ev });

    this.registerCallbacks({ ev, cb });
  };

  // listen = ({ ev, targetQuery = null, cb }) => {
  //   this.initEventObject({ ev });

  //   this.registerCallbacks({ ev, cb });

  //   if (typeof targetQuery !== "undefined" && targetQuery !== null) {
  //     this.defineTarget({ ev, targetQuery });
  //   }
  // };

  trigger = ({ ev, payload = null }) => {
    if (!this.typeManager.istypeString(ev)) {
      throw "EventManager.trigger: eventName must be a string";
    }

    if (
      typeof payload?.targetQuery !== "undefined" &&
      payload?.targetQuery !== null
    ) {
      this.defineTarget({ ev, targetQuery: payload.targetQuery });
    }

    // if (
    //   typeof payload?.targetQuery !== "undefined" &&
    //   payload?.targetQuery !== null
    // ) {
    //   this.triggerEventTargetedDomElement({
    //     ev,
    //     targetQuery: payload.targetQuery,
    //   });
    // }

    // if (typeof payload?.targetQuery === "undefined") {
    //   this.fireCallbacks({ ev, payload });
    // }

    // this.fireCallbacks({ ev, payload });

    const cbFn = this.eventCollection?.[ev.toString()]?.["cb"];
    const eventPayload = this.eventCollection?.[ev.toString()]?.payload;

    if (typeof cbFn === "function") {
      if (typeof eventPayload?.targets !== "undefined") {
        eventPayload.targets.forEach((domTarget) => {
          cbFn({ domTarget, eventPayload });
        });
      } else {
        cbFn(eventPayload);
      }
    }

    if (Array.isArray(cbFn)) {
      cbFn.forEach((fn) => {
        if (typeof eventPayload?.targets !== "undefined") {
          eventPayload.targets.forEach((domTarget) => {
            fn({ domTarget, eventPayload });
          });
        } else {
          fn(eventPayload);
        }
      });
    }
  };

  // triggerEventTargetedDomElement = ({ ev, targetQuery = null }) => {
  //   if (!this.typeManager.istypeString(targetQuery)) {
  //     throw "EventManager.trigger: targetQuery must be a string";
  //   }

  //   this.defineTarget({ ev, targetQuery });

  //   const targets = this.eventCollection?.[ev.toString()]?.targets;

  //   if (targets.length > 0) {
  //     targets?.forEach((target) => {
  //       this.fireCallbacks({ ev, target });
  //     });
  //   }
  // };

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

    // elementsCollection?.forEach((el) => {
    //   this.eventCollection?.[ev.toString()]?.targets?.push(el);
    // });

    let eventPayload = this.eventCollection?.[ev.toString()]?.payload;
    eventPayload.targets = [];

    elementsCollection?.forEach((el) => {
      eventPayload.targets.push(el);
    });
  };

  registerCallbacks = ({ ev, cb }) => {
    if (typeof cb == "function") {
      this.eventCollection[ev.toString()]["cb"] = (args) => cb(args);
    }

    if (Array.isArray(cb)) {
      let callbacks = [];

      cb.forEach((item) => {
        callbacks.push(item);
      });

      this.eventCollection[ev.toString()]["cb"] = callbacks;
    }
  };

  bulkAttachEvent = ({ elements, el, ev, cb, debug = false }) => {
    const _el = elements || el; // manage retroactive calls

    if (debug) {
      console.log({
        element: _el,
        event: ev,
        callback_called: cb,
      });

      if (!_el) {
        console.error(
          `EventManager 'bulkAttachEvent': Elements not found: event ${ev} - cb ${cb}`
        );
      }

      cb = () => console.log("Debug mode: event fired");
    }

    if (!_el) {
      return;
    }

    if (this._dom.isMultipleElements(_el)) {
      Object.keys(_el).forEach((idx) => {
        _el[parseInt(idx, 10)].addEventListener(ev, cb);
      });
    } else {
      this.attachEvent({ el: _el, ev, cb });
    }
  };

  attachEvent = ({ el, ev, cb, debug = false }) => {
    if (debug) {
      console.log({
        element: el,
        event: ev,
        callback_called: cb,
      });

      if (!el) {
        console.error(
          `EventManager 'attachEvent': Elements not found: event ${ev} - cb ${cb}`
        );
      }

      cb = () => console.log("Debug mode: event fired");
    }

    if (!el) {
      return;
    }

    if (this._dom.isSingleElement(el)) {
      el.addEventListener(ev, cb);
    }
  };

  scrollStop = ({ cb, refresh = 66 }) => {
    // Make sure a valid callback was provided
    if (!cb || typeof cb !== "function") return;

    // Setup scrolling variable
    let isScrolling;

    // Listen for scroll events
    window.addEventListener(
      "scroll",
      function (e) {
        // Clear our timeout throughout the scroll
        window.clearTimeout(isScrolling);

        // Set a timeout to run after scrolling ends
        isScrolling = setTimeout(cb, refresh);
      },
      false
    );
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

    const value = sessionStorage.getItem(key);

    if (value === "null") {
      return null;
    }

    if (value === "undefined") {
      return false;
    }

    if (value.charAt(0) === "{") {
      return JSON.parse(value);
    }

    return value;
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
    dataActionState: "data-action-state",
    dataStyle: "data-style",
  };

  visibilityState = {
    visible: "visible",
    hidden: "hidden",
    clamped: "clamped",
  };

  isMobile = false;

  constructor(eventManager) {
    this._event = eventManager;
    this.init();
  }

  init = () => {
    this.isMobileViewport();
    this.listenViewportResize();
  };

  elType = (el) => {
    if (typeof el === "undefined" || el === null) {
      throw "DomManage elType: parameter must be a string";
    }
    return Object.prototype.toString.call(el);
  };

  isMultipleElements = (el) => {
    const elType = this.elType(el);

    return elType === "[object HTMLCollection]" ||
      elType === "[object NodeList]"
      ? true
      : false;
  };

  isSingleElement = (el) => {
    const elType = this.elType(el);
    return elType.includes("Element");
  };

  el = (query) => {
    const el = document.querySelectorAll(query);

    if (el) {
      if (el.length === 0) {
        return false;
      }
      if (el.length === 1) {
        return el[0];
      }
      return el;
    }
  };

  getElementAttribute = (el) => {
    return {
      prodId: el?.getAttribute(this.domAttributes.productId),
      catId: el?.getAttribute(this.domAttributes.categoryId),
      price: el?.getAttribute(this.domAttributes.productPrice),
      visibility: el?.getAttribute(this.domAttributes.dataVisibility),
      actionState: el?.getAttribute(this.domAttributes.dataActionState),
    };
  };

  appendChild = ({ child = null, parent = null }) => {
    if (child && parent) {
      parent.appendChild(child);
    }
  };

  insertAfter = ({ target = null, newParent = null }) => {
    if (newParent) {
      newParent.parentNode?.insertBefore(target, newParent.nextSibling);
    }
  };

  scrollToTop = () => {
    window.scrollTo(0, 0);
  };

  show = (el) => {
    if (!el) {
      return;
    }

    if (this.isSingleElement(el)) {
      el.setAttribute(
        this.domAttributes.dataVisibility,
        this.visibilityState.visible
      );
    }

    if (this.isMultipleElements(el)) {
      Object.keys(el).forEach((idx) => {
        el[parseInt(idx, 10)].setAttribute(
          this.domAttributes.dataVisibility,
          this.visibilityState.visible
        );
      });
    }
  };

  hide = (el) => {
    if (!el) {
      return;
    }

    if (this.isSingleElement(el)) {
      el.setAttribute(
        this.domAttributes.dataVisibility,
        this.visibilityState.hidden
      );
    }

    if (this.isMultipleElements(el)) {
      Object.keys(el).forEach((idx) => {
        el[parseInt(idx, 10)].setAttribute(
          this.domAttributes.dataVisibility,
          this.visibilityState.hidden
        );
      });
    }
  };

  exists = (el) => {
    return this.isMultipleElements(el) || this.isSingleElement(el);
  };

  toggleVisibility = (el) => {
    if (!el) {
      return;
    }

    if (this.isSingleElement(el)) {
      if (this.shouldVisible(el)) {
        this.hide(el);
        return "hidden";
      }
      if (this.shouldHidden(el)) {
        this.show(el);
        return "visible";
      }
    }

    if (this.isMultipleElements(el)) {
      Object.keys(el).forEach((idx) => {
        if (this.shouldVisible(el[parseInt(idx, 10)])) {
          this.hide(el[parseInt(idx, 10)]);
          return "hidden";
        }
        if (this.shouldHidden(el[parseInt(idx, 10)])) {
          this.show(el[parseInt(idx, 10)]);
          return "visible";
        }
      });
    }
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
    console.log(el, visibility);
    return visibility === this.visibilityState.hidden ? true : false;
  };

  shouldClamped = (el) => {
    const { visibility } = this.getElementAttribute(el);
    return visibility === this.visibilityState.clamped ? true : false;
  };

  styleElements = () => {
    const elementsShouldStyled = this.el("*[data-style]");

    if (elementsShouldStyled === false) {
      return;
    }

    elementsShouldStyled.forEach((el) => {
      const jsonElementStyleData = el.getAttribute("data-style");

      if (
        typeof jsonElementStyleData === "undefined" ||
        jsonElementStyleData === null
      ) {
        return;
      }

      if (jsonElementStyleData.length === 0) {
        return;
      }

      const elementStyleData = JSON.parse(jsonElementStyleData);

      if (typeof elementStyleData["breakpoints"] === "undefined") {
        throw "DomManager - elementStyles: no breakpoints (min, max) are defined";
      }

      const { min, max } = elementStyleData["breakpoints"];

      Object.keys(elementStyleData).forEach((styleProperty) => {
        if (styleProperty === "breakpoints") {
          return;
        }

        if (
          window.innerWidth >= parseInt(min, 10) &&
          window.innerWidth <= parseInt(max, 10)
        ) {
          el.style[styleProperty] = elementStyleData[styleProperty];
        }
      });
    });
  };

  isMobileViewport = () => {
    this.isMobile = window.innerWidth < 478 ? true : false;
  };

  listenViewportResize = () => {
    window.addEventListener("resize", this.isMobileViewport);
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
  eventManager: new EventManager(new TypeManager(), new DomManager()),
  sessionManager: new SessionManager(new TypeManager()),
  cookieManager: new CookieManager(new TypeManager()),
  domManager: new DomManager(),
  httpRequestManager: {
    options: new HttpRequestOptions(),
    data: new HttpRequestData(),
  },
};

/** Trick to viewport units on mobile */

(function () {
  init();
  let vh = window.innerHeight * 0.01;
  setViewportHeight();

  function init() {
    // We listen to the resize event
    window.addEventListener("resize", () => {
      // We execute the same script as before
      document.documentElement.style.setProperty("--vh", `${vh}px`);
    });
  }

  function setViewportHeight() {
    document.documentElement.style.setProperty("--vh", `${vh}px`);
  }
})();
