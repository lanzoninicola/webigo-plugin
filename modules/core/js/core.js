class StateManager {
  state = {};

  setState = (prevState = {}, newState = {}, options = { cb: null }) => {
    let _newState = { ...prevState };
    const { cb } = options;

    // manage upto 2 levels of state object
    Object.keys(newState).forEach((stateItem) => {
      if (_newState[stateItem]) {
        Object.keys(newState[stateItem]).forEach((subStateItem) => {
          _newState[stateItem][subStateItem] =
            newState[stateItem][subStateItem];
        });
      } else {
        _newState = {
          ..._newState,
          [stateItem]: newState[stateItem],
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
  eventCollection = {};

  constructor(typeManager) {
    this.typeManager = typeManager;
  }

  addEvent = ({ eventName, callback }) => {
    if (typeof eventName === "undefined" && eventName === null) {
      console.error("EventManager.addEvent: eventName parameter is required");
    }

    if (!this.typeManager.istypeString(eventName)) {
      console.error("EventManager.addEvent: eventName must be a string");
      return;
    }

    this.eventCollection[eventName] = {
      cb: (args) => callback(args),
      targets: [],
      data: {},
    };
  };

  trigger = ({ eventName, targetQuery, data }) => {
    if (!this.typeManager.istypeString(eventName)) {
      console.error("EventManager.trigger: eventName must be a string");
      return;
    }

    if (!this.typeManager.istypeString(eventName)) {
      console.error("EventManager.trigger: eventName must be a string");
      return;
    }

    const cbFn = this.eventCollection?.[eventName]?.["cb"];

    if (typeof targetQuery !== "undefined" && targetQuery !== null) {
      this.defineTarget({ eventName, targetQuery });
    }

    const targets = this.eventCollection?.[eventName]?.targets;

    targets?.forEach((target) => cbFn(target));
  };

  defineTarget = ({ eventName, targetQuery }) => {
    if (!this.typeManager.istypeString(eventName)) {
      console.error("EventManager.defineTarget: eventName must be a string");
      return;
    }

    if (typeof targetQuery !== "undefined" && targetQuery !== null) {
      if (!this.typeManager.istypeString(targetQuery)) {
        console.error(
          "EventManager.defineTarget: targetQuery must be a string"
        );
        return;
      }
    }

    const elementsCollection = document.querySelectorAll(targetQuery);

    elementsCollection?.forEach((el) => {
      this.eventCollection?.[eventName]?.targets?.push(el);
    });
  };
}

class SessionManager {
  typeManager = null;

  constructor(typeManager) {
    this.typeManager = typeManager;
  }

  setSession = (key, value) => {
    if (this.typeManager.isUndefined(key) || this.typeManager.isNull(key)) {
      console.error(
        "Error to set browser sesssion. The key parameter is undefined"
      );
    }

    if (this.typeManager.isUndefined(key) || this.typeManager.isNull(key)) {
      console.error(
        "Error to set browser sesssion. The value parameter is undefined"
      );
    }

    let _value = value;

    if (typeof _value === "object") {
      _value = JSON.stringify(_value);
    }

    sessionStorage.setItem(key, _value);
  };
  getSession = (key) => {
    if (this.typeManager.isUndefined(key) || this.typeManager.isNull(key)) {
      console.error(
        "Error to get the browser sesssion for the key selected. The key parameter is undefined"
      );
    }

    sessionStorage.getItem(key);
  };
  removeSession = (key) => {
    if (this.typeManager.isUndefined(key) || this.typeManager.isNull(key)) {
      console.error(
        "Error to remove from the browser sesssion the key selected. The key parameter is undefined"
      );
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
      let c = ca[i];
      while (c.charAt(0) == " ") {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
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
    if (typeof value === null) {
      return true;
    }
  };
}

class DomManager {
  events = {
    click: "click",
  };

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

  getElementAttribute = (el) => {
    return {
      prodId: el?.getAttribute(this.domAttributes.productId),
      productPrice: el?.getAttribute(this.domAttributes.productPrice),
      catId: el?.getAttribute(this.domAttributes.categoryId),
      visibility: el?.getAttribute(this.domAttributes.dataVisibility),
    };
  };

  bulkAttachEvent = ({ elements, ev, cb }) => {
    if (elements) {
      Object.keys(elements).forEach((item) => {
        elements[item].addEventListener(ev, cb);
      });
    }
  };

  show = (el) => {
    el?.setAttribute(
      this.domAttributes.dataVisibility,
      this.visibilityState.visible
    );
  };

  hide = (el) => {
    el?.setAttribute(
      this.domAttributes.dataVisibility,
      this.visibilityState.hidden
    );
  };

  clamp = (el) => {
    el?.setAttribute(
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

const webigoHelper = {
  typeManager: new TypeManager(),
  stateManager: new StateManager(),
  eventManager: new EventManager(new TypeManager()),
  sessionManager: new SessionManager(new TypeManager()),
  cookieManager: new CookieManager(new TypeManager()),
  domManager: new DomManager(),
};
