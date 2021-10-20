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
        if (target[key] === undefined) {
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
