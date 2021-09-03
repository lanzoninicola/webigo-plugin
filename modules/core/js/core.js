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
  eventCollection = {};

  addEvent = (eventName, callback) => {
    this.eventCollection[eventName] = {
      cb: (args) => callback(args),
      target: [],
      data: null,
    };
  };

  trigger = ({ event, targetQuery }) => {
    const cbFn = this.eventCollection[event]["cb"];

    cbFn(document.querySelectorAll(targetQuery)[0]);
  };
}

const webigoHelper = {
  stateManager: new StateManager(),
  eventManager: new EventManager(),
  sessionManagement: {
    setSession: (key, value) => {
      if (typeof key === "undefined" || key === null) {
        console.error(
          "Error to set browser sesssion. The key parameter is undefined"
        );
      }

      if (typeof value === "undefined" || value === null) {
        console.error(
          "Error to set browser sesssion. The value parameter is undefined"
        );
      }

      let _value = value;

      if (typeof _value === "object") {
        _value = JSON.stringify(_value);
      }

      sessionStorage.setItem(key, _value);
    },
    getSession: (key) => {
      if (typeof key === "undefined" || key === null) {
        console.error(
          "Error to get the browser sesssion for the key selected. The key parameter is undefined"
        );
      }

      sessionStorage.getItem(key);
    },
    removeSession: (key) => {
      if (typeof key === "undefined" || key === null) {
        console.error(
          "Error to remove from the browser sesssion the key selected. The key parameter is undefined"
        );
      }

      sessionStorage.removeItem(key);
    },
  },
  cookieManagement: {
    getCookie: (cname) => {
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
    },
  },
  sayHello: () => {
    alert("Hello World!");
  },
};
