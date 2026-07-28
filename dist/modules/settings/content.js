var __create = Object.create;
var __defProp = Object.defineProperty;
var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
var __getOwnPropNames = Object.getOwnPropertyNames;
var __getProtoOf = Object.getPrototypeOf;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __commonJS = (cb, mod) => function __require() {
  return mod || (0, cb[__getOwnPropNames(cb)[0]])((mod = { exports: {} }).exports, mod), mod.exports;
};
var __copyProps = (to, from, except, desc) => {
  if (from && typeof from === "object" || typeof from === "function") {
    for (let key of __getOwnPropNames(from))
      if (!__hasOwnProp.call(to, key) && key !== except)
        __defProp(to, key, { get: () => from[key], enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable });
  }
  return to;
};
var __toESM = (mod, isNodeMode, target) => (target = mod != null ? __create(__getProtoOf(mod)) : {}, __copyProps(
  // If the importer is in node compatibility mode or this is not an ESM
  // file that has been converted to a CommonJS file using a Babel-
  // compatible transform (i.e. "__esModule" has not been set), then set
  // "default" to the CommonJS "module.exports" for node compatibility.
  isNodeMode || !mod || !mod.__esModule ? __defProp(target, "default", { value: mod, enumerable: true }) : target,
  mod
));

// wp-global:@wordpress/element
var require_element = __commonJS({
  "wp-global:@wordpress/element"(exports, module) {
    module.exports = window.wp.element;
  }
});

// wp-global:@wordpress/data
var require_data = __commonJS({
  "wp-global:@wordpress/data"(exports, module) {
    module.exports = window.wp.data;
  }
});

// wp-global:@wordpress/components
var require_components = __commonJS({
  "wp-global:@wordpress/components"(exports, module) {
    module.exports = window.wp.components;
  }
});

// wp-global:@wordpress/editor
var require_editor = __commonJS({
  "wp-global:@wordpress/editor"(exports, module) {
    module.exports = window.wp.editor;
  }
});

// wp-global:@wordpress/i18n
var require_i18n = __commonJS({
  "wp-global:@wordpress/i18n"(exports, module) {
    module.exports = window.wp.i18n;
  }
});

// wp-global:@wordpress/compose
var require_compose = __commonJS({
  "wp-global:@wordpress/compose"(exports, module) {
    module.exports = window.wp.compose;
  }
});

// wp-global:react
var require_react = __commonJS({
  "wp-global:react"(exports, module) {
    module.exports = window.React;
  }
});

// wp-global:react/jsx-runtime
var require_jsx_runtime = __commonJS({
  "wp-global:react/jsx-runtime"(exports, module) {
    module.exports = window.ReactJSXRuntime;
  }
});

// wp-global:@wordpress/primitives
var require_primitives = __commonJS({
  "wp-global:@wordpress/primitives"(exports, module) {
    module.exports = window.wp.primitives;
  }
});

// wp-global:@wordpress/private-apis
var require_private_apis = __commonJS({
  "wp-global:@wordpress/private-apis"(exports, module) {
    module.exports = window.wp.privateApis;
  }
});

// node_modules/fast-deep-equal/es6/index.js
var require_es6 = __commonJS({
  "node_modules/fast-deep-equal/es6/index.js"(exports, module) {
    "use strict";
    module.exports = function equal(a2, b2) {
      if (a2 === b2) return true;
      if (a2 && b2 && typeof a2 == "object" && typeof b2 == "object") {
        if (a2.constructor !== b2.constructor) return false;
        var length, i2, keys;
        if (Array.isArray(a2)) {
          length = a2.length;
          if (length != b2.length) return false;
          for (i2 = length; i2-- !== 0; )
            if (!equal(a2[i2], b2[i2])) return false;
          return true;
        }
        if (a2 instanceof Map && b2 instanceof Map) {
          if (a2.size !== b2.size) return false;
          for (i2 of a2.entries())
            if (!b2.has(i2[0])) return false;
          for (i2 of a2.entries())
            if (!equal(i2[1], b2.get(i2[0]))) return false;
          return true;
        }
        if (a2 instanceof Set && b2 instanceof Set) {
          if (a2.size !== b2.size) return false;
          for (i2 of a2.entries())
            if (!b2.has(i2[0])) return false;
          return true;
        }
        if (ArrayBuffer.isView(a2) && ArrayBuffer.isView(b2)) {
          length = a2.length;
          if (length != b2.length) return false;
          for (i2 = length; i2-- !== 0; )
            if (a2[i2] !== b2[i2]) return false;
          return true;
        }
        if (a2.constructor === RegExp) return a2.source === b2.source && a2.flags === b2.flags;
        if (a2.valueOf !== Object.prototype.valueOf) return a2.valueOf() === b2.valueOf();
        if (a2.toString !== Object.prototype.toString) return a2.toString() === b2.toString();
        keys = Object.keys(a2);
        length = keys.length;
        if (length !== Object.keys(b2).length) return false;
        for (i2 = length; i2-- !== 0; )
          if (!Object.prototype.hasOwnProperty.call(b2, keys[i2])) return false;
        for (i2 = length; i2-- !== 0; ) {
          var key = keys[i2];
          if (!equal(a2[key], b2[key])) return false;
        }
        return true;
      }
      return a2 !== a2 && b2 !== b2;
    };
  }
});

// wp-global:@wordpress/date
var require_date = __commonJS({
  "wp-global:@wordpress/date"(exports, module) {
    module.exports = window.wp.date;
  }
});

// node_modules/deepmerge/dist/cjs.js
var require_cjs = __commonJS({
  "node_modules/deepmerge/dist/cjs.js"(exports, module) {
    "use strict";
    var isMergeableObject = function isMergeableObject2(value) {
      return isNonNullObject(value) && !isSpecial(value);
    };
    function isNonNullObject(value) {
      return !!value && typeof value === "object";
    }
    function isSpecial(value) {
      var stringValue = Object.prototype.toString.call(value);
      return stringValue === "[object RegExp]" || stringValue === "[object Date]" || isReactElement(value);
    }
    var canUseSymbol = typeof Symbol === "function" && Symbol.for;
    var REACT_ELEMENT_TYPE = canUseSymbol ? Symbol.for("react.element") : 60103;
    function isReactElement(value) {
      return value.$$typeof === REACT_ELEMENT_TYPE;
    }
    function emptyTarget(val) {
      return Array.isArray(val) ? [] : {};
    }
    function cloneUnlessOtherwiseSpecified(value, options) {
      return options.clone !== false && options.isMergeableObject(value) ? deepmerge(emptyTarget(value), value, options) : value;
    }
    function defaultArrayMerge(target, source, options) {
      return target.concat(source).map(function(element) {
        return cloneUnlessOtherwiseSpecified(element, options);
      });
    }
    function getMergeFunction(key, options) {
      if (!options.customMerge) {
        return deepmerge;
      }
      var customMerge = options.customMerge(key);
      return typeof customMerge === "function" ? customMerge : deepmerge;
    }
    function getEnumerableOwnPropertySymbols(target) {
      return Object.getOwnPropertySymbols ? Object.getOwnPropertySymbols(target).filter(function(symbol) {
        return Object.propertyIsEnumerable.call(target, symbol);
      }) : [];
    }
    function getKeys(target) {
      return Object.keys(target).concat(getEnumerableOwnPropertySymbols(target));
    }
    function propertyIsOnObject(object, property) {
      try {
        return property in object;
      } catch (_) {
        return false;
      }
    }
    function propertyIsUnsafe(target, key) {
      return propertyIsOnObject(target, key) && !(Object.hasOwnProperty.call(target, key) && Object.propertyIsEnumerable.call(target, key));
    }
    function mergeObject(target, source, options) {
      var destination = {};
      if (options.isMergeableObject(target)) {
        getKeys(target).forEach(function(key) {
          destination[key] = cloneUnlessOtherwiseSpecified(target[key], options);
        });
      }
      getKeys(source).forEach(function(key) {
        if (propertyIsUnsafe(target, key)) {
          return;
        }
        if (propertyIsOnObject(target, key) && options.isMergeableObject(source[key])) {
          destination[key] = getMergeFunction(key, options)(target[key], source[key], options);
        } else {
          destination[key] = cloneUnlessOtherwiseSpecified(source[key], options);
        }
      });
      return destination;
    }
    function deepmerge(target, source, options) {
      options = options || {};
      options.arrayMerge = options.arrayMerge || defaultArrayMerge;
      options.isMergeableObject = options.isMergeableObject || isMergeableObject;
      options.cloneUnlessOtherwiseSpecified = cloneUnlessOtherwiseSpecified;
      var sourceIsArray = Array.isArray(source);
      var targetIsArray = Array.isArray(target);
      var sourceAndTargetTypesMatch = sourceIsArray === targetIsArray;
      if (!sourceAndTargetTypesMatch) {
        return cloneUnlessOtherwiseSpecified(source, options);
      } else if (sourceIsArray) {
        return options.arrayMerge(target, source, options);
      } else {
        return mergeObject(target, source, options);
      }
    }
    deepmerge.all = function deepmergeAll(array, options) {
      if (!Array.isArray(array)) {
        throw new Error("first argument should be an array");
      }
      return array.reduce(function(prev, next) {
        return deepmerge(prev, next, options);
      }, {});
    };
    var deepmerge_1 = deepmerge;
    module.exports = deepmerge_1;
  }
});

// assets/js/admin-settings/content.js
var import_element33 = __toESM(require_element());
var import_data = __toESM(require_data());
var import_components26 = __toESM(require_components());
var import_editor = __toESM(require_editor());
var import_i18n22 = __toESM(require_i18n());

// node_modules/@base-ui/utils/useRefWithInit.mjs
var React = __toESM(require_react(), 1);
var UNINITIALIZED = {};
function useRefWithInit(init, initArg) {
  const ref = React.useRef(UNINITIALIZED);
  if (ref.current === UNINITIALIZED) {
    ref.current = init(initArg);
  }
  return ref;
}

// node_modules/@base-ui/utils/warn.mjs
var set;
if (true) {
  set = /* @__PURE__ */ new Set();
}
function warn(...messages) {
  if (true) {
    const messageKey = messages.join(" ");
    if (!set.has(messageKey)) {
      set.add(messageKey);
      console.warn(`Base UI: ${messageKey}`);
    }
  }
}

// node_modules/@base-ui/react/internals/useRenderElement.mjs
var React4 = __toESM(require_react(), 1);

// node_modules/@base-ui/utils/useMergedRefs.mjs
function useMergedRefs(a2, b2, c2, d2) {
  const forkRef = useRefWithInit(createForkRef).current;
  if (didChange(forkRef, a2, b2, c2, d2)) {
    update(forkRef, [a2, b2, c2, d2]);
  }
  return forkRef.callback;
}
function useMergedRefsN(refs) {
  const forkRef = useRefWithInit(createForkRef).current;
  if (didChangeN(forkRef, refs)) {
    update(forkRef, refs);
  }
  return forkRef.callback;
}
function createForkRef() {
  return {
    callback: null,
    cleanup: null,
    refs: []
  };
}
function didChange(forkRef, a2, b2, c2, d2) {
  return forkRef.refs[0] !== a2 || forkRef.refs[1] !== b2 || forkRef.refs[2] !== c2 || forkRef.refs[3] !== d2;
}
function didChangeN(forkRef, newRefs) {
  return forkRef.refs.length !== newRefs.length || forkRef.refs.some((ref, index) => ref !== newRefs[index]);
}
function update(forkRef, refs) {
  forkRef.refs = refs;
  if (refs.every((ref) => ref == null)) {
    forkRef.callback = null;
    return;
  }
  forkRef.callback = (instance) => {
    if (forkRef.cleanup) {
      forkRef.cleanup();
      forkRef.cleanup = null;
    }
    if (instance != null) {
      const cleanupCallbacks = Array(refs.length).fill(null);
      for (let i2 = 0; i2 < refs.length; i2 += 1) {
        const ref = refs[i2];
        if (ref == null) {
          continue;
        }
        switch (typeof ref) {
          case "function": {
            const refCleanup = ref(instance);
            if (typeof refCleanup === "function") {
              cleanupCallbacks[i2] = refCleanup;
            }
            break;
          }
          case "object": {
            ref.current = instance;
            break;
          }
          default:
        }
      }
      forkRef.cleanup = () => {
        for (let i2 = 0; i2 < refs.length; i2 += 1) {
          const ref = refs[i2];
          if (ref == null) {
            continue;
          }
          switch (typeof ref) {
            case "function": {
              const cleanupCallback = cleanupCallbacks[i2];
              if (typeof cleanupCallback === "function") {
                cleanupCallback();
              } else {
                ref(null);
              }
              break;
            }
            case "object": {
              ref.current = null;
              break;
            }
            default:
          }
        }
      };
    }
  };
}

// node_modules/@base-ui/utils/getReactElementRef.mjs
var React3 = __toESM(require_react(), 1);

// node_modules/@base-ui/utils/reactVersion.mjs
var React2 = __toESM(require_react(), 1);
var majorVersion = parseInt(React2.version, 10);
function isReactVersionAtLeast(reactVersionToCheck) {
  return majorVersion >= reactVersionToCheck;
}

// node_modules/@base-ui/utils/getReactElementRef.mjs
function getReactElementRef(element) {
  if (!/* @__PURE__ */ React3.isValidElement(element)) {
    return null;
  }
  const reactElement = element;
  const propsWithRef = reactElement.props;
  return (isReactVersionAtLeast(19) ? propsWithRef?.ref : reactElement.ref) ?? null;
}

// node_modules/@base-ui/utils/mergeObjects.mjs
function mergeObjects(a2, b2) {
  if (a2 && !b2) {
    return a2;
  }
  if (!a2 && b2) {
    return b2;
  }
  if (a2 || b2) {
    return {
      ...a2,
      ...b2
    };
  }
  return void 0;
}

// node_modules/@base-ui/utils/empty.mjs
var EMPTY_ARRAY = Object.freeze([]);
var EMPTY_OBJECT = Object.freeze({});

// node_modules/@base-ui/react/internals/getStateAttributesProps.mjs
function getStateAttributesProps(state, customMapping) {
  const props = {};
  for (const key in state) {
    const value = state[key];
    if (customMapping?.hasOwnProperty(key)) {
      const customProps = customMapping[key](value);
      if (customProps != null) {
        Object.assign(props, customProps);
      }
      continue;
    }
    if (value === true) {
      props[`data-${key.toLowerCase()}`] = "";
    } else if (value) {
      props[`data-${key.toLowerCase()}`] = value.toString();
    }
  }
  return props;
}

// node_modules/@base-ui/react/utils/resolveClassName.mjs
function resolveClassName(className, state) {
  return typeof className === "function" ? className(state) : className;
}

// node_modules/@base-ui/react/utils/resolveStyle.mjs
function resolveStyle(style, state) {
  return typeof style === "function" ? style(state) : style;
}

// node_modules/@base-ui/react/merge-props/mergeProps.mjs
var EMPTY_PROPS = {};
function mergeProps(a2, b2, c2, d2, e2) {
  if (!c2 && !d2 && !e2 && !a2) {
    return createInitialMergedProps(b2);
  }
  let merged = createInitialMergedProps(a2);
  if (b2) {
    merged = mergeInto(merged, b2);
  }
  if (c2) {
    merged = mergeInto(merged, c2);
  }
  if (d2) {
    merged = mergeInto(merged, d2);
  }
  if (e2) {
    merged = mergeInto(merged, e2);
  }
  return merged;
}
function mergePropsN(props) {
  if (props.length === 0) {
    return EMPTY_PROPS;
  }
  if (props.length === 1) {
    return createInitialMergedProps(props[0]);
  }
  let merged = createInitialMergedProps(props[0]);
  for (let i2 = 1; i2 < props.length; i2 += 1) {
    merged = mergeInto(merged, props[i2]);
  }
  return merged;
}
function createInitialMergedProps(inputProps) {
  if (isPropsGetter(inputProps)) {
    return {
      ...resolvePropsGetter(inputProps, EMPTY_PROPS)
    };
  }
  return copyInitialProps(inputProps);
}
function mergeInto(merged, inputProps) {
  if (isPropsGetter(inputProps)) {
    return resolvePropsGetter(inputProps, merged);
  }
  return mutablyMergeInto(merged, inputProps);
}
function copyInitialProps(inputProps) {
  const copiedProps = {
    ...inputProps
  };
  for (const propName in copiedProps) {
    const propValue = copiedProps[propName];
    if (isEventHandler(propName, propValue)) {
      copiedProps[propName] = wrapEventHandler(propValue);
    }
  }
  return copiedProps;
}
function mutablyMergeInto(mergedProps, externalProps) {
  if (!externalProps) {
    return mergedProps;
  }
  for (const propName in externalProps) {
    const externalPropValue = externalProps[propName];
    switch (propName) {
      case "style": {
        mergedProps[propName] = mergeObjects(mergedProps.style, externalPropValue);
        break;
      }
      case "className": {
        mergedProps[propName] = mergeClassNames(mergedProps.className, externalPropValue);
        break;
      }
      default: {
        if (isEventHandler(propName, externalPropValue)) {
          mergedProps[propName] = mergeEventHandlers(mergedProps[propName], externalPropValue);
        } else {
          mergedProps[propName] = externalPropValue;
        }
      }
    }
  }
  return mergedProps;
}
function isEventHandler(key, value) {
  const code0 = key.charCodeAt(0);
  const code1 = key.charCodeAt(1);
  const code2 = key.charCodeAt(2);
  return code0 === 111 && code1 === 110 && code2 >= 65 && code2 <= 90 && (typeof value === "function" || typeof value === "undefined");
}
function isPropsGetter(inputProps) {
  return typeof inputProps === "function";
}
function resolvePropsGetter(inputProps, previousProps) {
  if (isPropsGetter(inputProps)) {
    return inputProps(previousProps);
  }
  return inputProps ?? EMPTY_PROPS;
}
function mergeEventHandlers(ourHandler, theirHandler) {
  if (!theirHandler) {
    return ourHandler;
  }
  if (!ourHandler) {
    return wrapEventHandler(theirHandler);
  }
  return (...args) => {
    const event = args[0];
    if (isSyntheticEvent(event)) {
      const baseUIEvent = event;
      makeEventPreventable(baseUIEvent);
      const result2 = theirHandler(...args);
      if (!baseUIEvent.baseUIHandlerPrevented) {
        ourHandler?.(...args);
      }
      return result2;
    }
    const result = theirHandler(...args);
    ourHandler?.(...args);
    return result;
  };
}
function wrapEventHandler(handler) {
  if (!handler) {
    return handler;
  }
  return (...args) => {
    const event = args[0];
    if (isSyntheticEvent(event)) {
      makeEventPreventable(event);
    }
    return handler(...args);
  };
}
function makeEventPreventable(event) {
  event.preventBaseUIHandler = () => {
    event.baseUIHandlerPrevented = true;
  };
  return event;
}
function mergeClassNames(ourClassName, theirClassName) {
  if (theirClassName) {
    if (ourClassName) {
      return theirClassName + " " + ourClassName;
    }
    return theirClassName;
  }
  return ourClassName;
}
function isSyntheticEvent(event) {
  return event != null && typeof event === "object" && "nativeEvent" in event;
}

// node_modules/@base-ui/react/internals/useRenderElement.mjs
var import_react = __toESM(require_react(), 1);
function useRenderElement(element, componentProps, params = {}) {
  const renderProp = componentProps.render;
  const outProps = useRenderElementProps(componentProps, params);
  if (params.enabled === false) {
    return null;
  }
  const state = params.state ?? EMPTY_OBJECT;
  return evaluateRenderProp(element, renderProp, outProps, state);
}
function useRenderElementProps(componentProps, params = {}) {
  const {
    className: classNameProp,
    style: styleProp,
    render: renderProp
  } = componentProps;
  const {
    state = EMPTY_OBJECT,
    ref,
    props,
    stateAttributesMapping,
    enabled = true
  } = params;
  const className = enabled ? resolveClassName(classNameProp, state) : void 0;
  const style = enabled ? resolveStyle(styleProp, state) : void 0;
  const stateProps = enabled ? getStateAttributesProps(state, stateAttributesMapping) : EMPTY_OBJECT;
  const resolvedProps = enabled && props ? resolveRenderFunctionProps(props) : void 0;
  const outProps = enabled ? mergeObjects(stateProps, resolvedProps) ?? {} : EMPTY_OBJECT;
  if (typeof document !== "undefined") {
    if (!enabled) {
      useMergedRefs(null, null);
    } else if (Array.isArray(ref)) {
      outProps.ref = useMergedRefsN([outProps.ref, getReactElementRef(renderProp), ...ref]);
    } else {
      outProps.ref = useMergedRefs(outProps.ref, getReactElementRef(renderProp), ref);
    }
  }
  if (!enabled) {
    return EMPTY_OBJECT;
  }
  if (className !== void 0) {
    outProps.className = mergeClassNames(outProps.className, className);
  }
  if (style !== void 0) {
    outProps.style = mergeObjects(outProps.style, style);
  }
  return outProps;
}
function resolveRenderFunctionProps(props) {
  if (Array.isArray(props)) {
    return mergePropsN(props);
  }
  return mergeProps(void 0, props);
}
var REACT_LAZY_TYPE = Symbol.for("react.lazy");
var COMPONENT_IDENTIFIER_PATTERN = /^[A-Z][A-Za-z0-9$]*$/;
var LOWERCASE_CHARACTER_PATTERN = /[a-z]/;
function evaluateRenderProp(element, render4, props, state) {
  if (render4) {
    if (typeof render4 === "function") {
      if (true) {
        warnIfRenderPropLooksLikeComponent(render4);
      }
      return render4(props, state);
    }
    const mergedProps = mergeProps(props, render4.props);
    mergedProps.ref = props.ref;
    let newElement = render4;
    if (newElement?.$$typeof === REACT_LAZY_TYPE) {
      const children = React4.Children.toArray(render4);
      newElement = children[0];
    }
    if (true) {
      if (!/* @__PURE__ */ React4.isValidElement(newElement)) {
        throw new Error(["Base UI: The `render` prop was provided an invalid React element as `React.isValidElement(render)` is `false`.", "A valid React element must be provided to the `render` prop because it is cloned with props to replace the default element.", "https://base-ui.com/r/invalid-render-prop"].join("\n"));
      }
    }
    return /* @__PURE__ */ React4.cloneElement(newElement, mergedProps);
  }
  if (element) {
    if (typeof element === "string") {
      return renderTag(element, props);
    }
  }
  throw new Error(true ? "Base UI: Render element or function are not defined." : formatErrorMessage_default(8));
}
function warnIfRenderPropLooksLikeComponent(renderFn) {
  const functionName = renderFn.name;
  if (functionName.length === 0) {
    return;
  }
  if (!COMPONENT_IDENTIFIER_PATTERN.test(functionName)) {
    return;
  }
  if (!LOWERCASE_CHARACTER_PATTERN.test(functionName)) {
    return;
  }
  warn(`The \`render\` prop received a function named \`${functionName}\` that starts with an uppercase letter.`, "This usually means a React component was passed directly as `render={Component}`.", "Base UI calls `render` as a plain function, which can break the Rules of Hooks during reconciliation.", "If this is an intentional render callback, rename it to start with a lowercase letter.", "Use `render={<Component />}` or `render={(props) => <Component {...props} />}` instead.", "https://base-ui.com/r/invalid-render-prop");
}
function renderTag(Tag, props) {
  if (Tag === "button") {
    return /* @__PURE__ */ (0, import_react.createElement)("button", {
      type: "button",
      ...props,
      key: props.key
    });
  }
  if (Tag === "img") {
    return /* @__PURE__ */ (0, import_react.createElement)("img", {
      alt: "",
      ...props,
      key: props.key
    });
  }
  return /* @__PURE__ */ React4.createElement(Tag, props);
}

// node_modules/@base-ui/react/use-render/useRender.mjs
function useRender(params) {
  return useRenderElement(params.defaultTagName ?? "div", params, params);
}

// node_modules/clsx/dist/clsx.mjs
function r(e2) {
  var t2, f2, n2 = "";
  if ("string" == typeof e2 || "number" == typeof e2) n2 += e2;
  else if ("object" == typeof e2) if (Array.isArray(e2)) {
    var o2 = e2.length;
    for (t2 = 0; t2 < o2; t2++) e2[t2] && (f2 = r(e2[t2])) && (n2 && (n2 += " "), n2 += f2);
  } else for (f2 in e2) e2[f2] && (n2 && (n2 += " "), n2 += f2);
  return n2;
}
function clsx() {
  for (var e2, t2, f2 = 0, n2 = "", o2 = arguments.length; f2 < o2; f2++) (e2 = arguments[f2]) && (t2 = r(e2)) && (n2 && (n2 += " "), n2 += t2);
  return n2;
}
var clsx_default = clsx;

// node_modules/@wordpress/ui/build-module/badge/badge.mjs
var import_element = __toESM(require_element(), 1);
if (typeof document !== "undefined" && !document.head.querySelector("style[data-wp-hash='244b5c59c0']")) {
  const style = document.createElement("style");
  style.setAttribute("data-wp-hash", "244b5c59c0");
  style.appendChild(document.createTextNode('@layer wp-ui-utilities, wp-ui-components, wp-ui-compositions, wp-ui-overrides;@layer wp-ui-components{._96e6251aad1a6136__badge{border-radius:var(--wpds-border-radius-lg,8px);font-family:var(--wpds-font-family-body,-apple-system,system-ui,"Segoe UI","Roboto","Oxygen-Sans","Ubuntu","Cantarell","Helvetica Neue",sans-serif);font-size:var(--wpds-font-size-sm,12px);font-weight:var(--wpds-font-weight-regular,400);line-height:var(--wpds-font-line-height-xs,16px);padding-block:var(--wpds-dimension-padding-xs,4px);padding-inline:var(--wpds-dimension-padding-sm,8px)}._99f7158cb520f750__is-high-intent{background-color:var(--wpds-color-bg-surface-error,#f6e6e3);color:var(--wpds-color-fg-content-error,#470000)}.c20ebef2365bc8b7__is-medium-intent{background-color:var(--wpds-color-bg-surface-warning,#fde6bd);color:var(--wpds-color-fg-content-warning,#2e1900)}._365e1626c6202e52__is-low-intent{background-color:var(--wpds-color-bg-surface-caution,#fee994);color:var(--wpds-color-fg-content-caution,#281d00)}._33f8198127ddf4ef__is-stable-intent{background-color:var(--wpds-color-bg-surface-success,#c5f7cc);color:var(--wpds-color-fg-content-success,#002900)}._04c1aca8fc449412__is-informational-intent{background-color:var(--wpds-color-bg-surface-info,#deebfa);color:var(--wpds-color-fg-content-info,#001b4f)}._90726e69d495ec19__is-draft-intent{background-color:var(--wpds-color-bg-surface-neutral-weak,#f0f0f0);color:var(--wpds-color-fg-content-neutral,#1e1e1e)}._898f4a544993bd39__is-none-intent{background-color:var(--wpds-color-bg-surface-neutral,#f8f8f8);color:var(--wpds-color-fg-content-neutral-weak,#6d6d6d)}}'));
  document.head.appendChild(style);
}
var style_default = { "badge": "_96e6251aad1a6136__badge", "is-high-intent": "_99f7158cb520f750__is-high-intent", "is-medium-intent": "c20ebef2365bc8b7__is-medium-intent", "is-low-intent": "_365e1626c6202e52__is-low-intent", "is-stable-intent": "_33f8198127ddf4ef__is-stable-intent", "is-informational-intent": "_04c1aca8fc449412__is-informational-intent", "is-draft-intent": "_90726e69d495ec19__is-draft-intent", "is-none-intent": "_898f4a544993bd39__is-none-intent" };
var Badge = (0, import_element.forwardRef)(function Badge2({ children, intent = "none", render: render4, className, ...props }, ref) {
  const element = useRender({
    render: render4,
    defaultTagName: "span",
    ref,
    props: mergeProps(props, {
      className: clsx_default(
        style_default.badge,
        style_default[`is-${intent}-intent`],
        className
      ),
      children
    })
  });
  return element;
});

// node_modules/@wordpress/icons/build-module/library/chevron-down.mjs
var import_primitives = __toESM(require_primitives(), 1);
var import_jsx_runtime = __toESM(require_jsx_runtime(), 1);
var chevron_down_default = /* @__PURE__ */ (0, import_jsx_runtime.jsx)(import_primitives.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0, import_jsx_runtime.jsx)(import_primitives.Path, { d: "M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z" }) });

// node_modules/@wordpress/icons/build-module/library/chevron-up.mjs
var import_primitives2 = __toESM(require_primitives(), 1);
var import_jsx_runtime2 = __toESM(require_jsx_runtime(), 1);
var chevron_up_default = /* @__PURE__ */ (0, import_jsx_runtime2.jsx)(import_primitives2.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0, import_jsx_runtime2.jsx)(import_primitives2.Path, { d: "M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z" }) });

// node_modules/@wordpress/icons/build-module/library/close-small.mjs
var import_primitives3 = __toESM(require_primitives(), 1);
var import_jsx_runtime3 = __toESM(require_jsx_runtime(), 1);
var close_small_default = /* @__PURE__ */ (0, import_jsx_runtime3.jsx)(import_primitives3.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0, import_jsx_runtime3.jsx)(import_primitives3.Path, { d: "M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z" }) });

// node_modules/@wordpress/icons/build-module/library/envelope.mjs
var import_primitives4 = __toESM(require_primitives(), 1);
var import_jsx_runtime4 = __toESM(require_jsx_runtime(), 1);
var envelope_default = /* @__PURE__ */ (0, import_jsx_runtime4.jsx)(import_primitives4.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0, import_jsx_runtime4.jsx)(import_primitives4.Path, { fillRule: "evenodd", clipRule: "evenodd", d: "M3 7c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7Zm2-.5h14c.3 0 .5.2.5.5v1L12 13.5 4.5 7.9V7c0-.3.2-.5.5-.5Zm-.5 3.3V17c0 .3.2.5.5.5h14c.3 0 .5-.2.5-.5V9.8L12 15.4 4.5 9.8Z" }) });

// node_modules/@wordpress/icons/build-module/library/error.mjs
var import_primitives5 = __toESM(require_primitives(), 1);
var import_jsx_runtime5 = __toESM(require_jsx_runtime(), 1);
var error_default = /* @__PURE__ */ (0, import_jsx_runtime5.jsx)(import_primitives5.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0, import_jsx_runtime5.jsx)(import_primitives5.Path, { fillRule: "evenodd", clipRule: "evenodd", d: "M12.218 5.377a.25.25 0 0 0-.436 0l-7.29 12.96a.25.25 0 0 0 .218.373h14.58a.25.25 0 0 0 .218-.372l-7.29-12.96Zm-1.743-.735c.669-1.19 2.381-1.19 3.05 0l7.29 12.96a1.75 1.75 0 0 1-1.525 2.608H4.71a1.75 1.75 0 0 1-1.525-2.608l7.29-12.96ZM12.75 17.46h-1.5v-1.5h1.5v1.5Zm-1.5-3h1.5v-5h-1.5v5Z" }) });

// node_modules/@wordpress/icons/build-module/library/link.mjs
var import_primitives6 = __toESM(require_primitives(), 1);
var import_jsx_runtime6 = __toESM(require_jsx_runtime(), 1);
var link_default = /* @__PURE__ */ (0, import_jsx_runtime6.jsx)(import_primitives6.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0, import_jsx_runtime6.jsx)(import_primitives6.Path, { d: "M10 17.389H8.444A5.194 5.194 0 1 1 8.444 7H10v1.5H8.444a3.694 3.694 0 0 0 0 7.389H10v1.5ZM14 7h1.556a5.194 5.194 0 0 1 0 10.39H14v-1.5h1.556a3.694 3.694 0 0 0 0-7.39H14V7Zm-4.5 6h5v-1.5h-5V13Z" }) });

// node_modules/@wordpress/icons/build-module/library/mobile.mjs
var import_primitives7 = __toESM(require_primitives(), 1);
var import_jsx_runtime7 = __toESM(require_jsx_runtime(), 1);
var mobile_default = /* @__PURE__ */ (0, import_jsx_runtime7.jsx)(import_primitives7.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0, import_jsx_runtime7.jsx)(import_primitives7.Path, { d: "M15 4H9c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm.5 14c0 .3-.2.5-.5.5H9c-.3 0-.5-.2-.5-.5V6c0-.3.2-.5.5-.5h6c.3 0 .5.2.5.5v12zm-4.5-.5h2V16h-2v1.5z" }) });

// node_modules/@wordpress/icons/build-module/library/pencil.mjs
var import_primitives8 = __toESM(require_primitives(), 1);
var import_jsx_runtime8 = __toESM(require_jsx_runtime(), 1);
var pencil_default = /* @__PURE__ */ (0, import_jsx_runtime8.jsx)(import_primitives8.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0, import_jsx_runtime8.jsx)(import_primitives8.Path, { d: "m19 7-3-3-8.5 8.5-1 4 4-1L19 7Zm-7 11.5H5V20h7v-1.5Z" }) });

// node_modules/@wordpress/icons/build-module/library/seen.mjs
var import_primitives9 = __toESM(require_primitives(), 1);
var import_jsx_runtime9 = __toESM(require_jsx_runtime(), 1);
var seen_default = /* @__PURE__ */ (0, import_jsx_runtime9.jsx)(import_primitives9.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0, import_jsx_runtime9.jsx)(import_primitives9.Path, { d: "M3.99961 13C4.67043 13.3354 4.6703 13.3357 4.67017 13.3359L4.67298 13.3305C4.67621 13.3242 4.68184 13.3135 4.68988 13.2985C4.70595 13.2686 4.7316 13.2218 4.76695 13.1608C4.8377 13.0385 4.94692 12.8592 5.09541 12.6419C5.39312 12.2062 5.84436 11.624 6.45435 11.0431C7.67308 9.88241 9.49719 8.75 11.9996 8.75C14.502 8.75 16.3261 9.88241 17.5449 11.0431C18.1549 11.624 18.6061 12.2062 18.9038 12.6419C19.0523 12.8592 19.1615 13.0385 19.2323 13.1608C19.2676 13.2218 19.2933 13.2686 19.3093 13.2985C19.3174 13.3135 19.323 13.3242 19.3262 13.3305L19.3291 13.3359C19.3289 13.3357 19.3288 13.3354 19.9996 13C20.6704 12.6646 20.6703 12.6643 20.6701 12.664L20.6697 12.6632L20.6688 12.6614L20.6662 12.6563L20.6583 12.6408C20.6517 12.6282 20.6427 12.6108 20.631 12.5892C20.6078 12.5459 20.5744 12.4852 20.5306 12.4096C20.4432 12.2584 20.3141 12.0471 20.1423 11.7956C19.7994 11.2938 19.2819 10.626 18.5794 9.9569C17.1731 8.61759 14.9972 7.25 11.9996 7.25C9.00203 7.25 6.82614 8.61759 5.41987 9.9569C4.71736 10.626 4.19984 11.2938 3.85694 11.7956C3.68511 12.0471 3.55605 12.2584 3.4686 12.4096C3.42484 12.4852 3.39142 12.5459 3.36818 12.5892C3.35656 12.6108 3.34748 12.6282 3.34092 12.6408L3.33297 12.6563L3.33041 12.6614L3.32948 12.6632L3.32911 12.664C3.32894 12.6643 3.32879 12.6646 3.99961 13ZM11.9996 16C13.9326 16 15.4996 14.433 15.4996 12.5C15.4996 10.567 13.9326 9 11.9996 9C10.0666 9 8.49961 10.567 8.49961 12.5C8.49961 14.433 10.0666 16 11.9996 16Z" }) });

// node_modules/@wordpress/icons/build-module/library/unseen.mjs
var import_primitives10 = __toESM(require_primitives(), 1);
var import_jsx_runtime10 = __toESM(require_jsx_runtime(), 1);
var unseen_default = /* @__PURE__ */ (0, import_jsx_runtime10.jsx)(import_primitives10.SVG, { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", children: /* @__PURE__ */ (0, import_jsx_runtime10.jsx)(import_primitives10.Path, { d: "M20.7 12.7s0-.1-.1-.2c0-.2-.2-.4-.4-.6-.3-.5-.9-1.2-1.6-1.8-.7-.6-1.5-1.3-2.6-1.8l-.6 1.4c.9.4 1.6 1 2.1 1.5.6.6 1.1 1.2 1.4 1.6.1.2.3.4.3.5v.1l.7-.3.7-.3Zm-5.2-9.3-1.8 4c-.5-.1-1.1-.2-1.7-.2-3 0-5.2 1.4-6.6 2.7-.7.7-1.2 1.3-1.6 1.8-.2.3-.3.5-.4.6 0 0 0 .1-.1.2s0 0 .7.3l.7.3V13c0-.1.2-.3.3-.5.3-.4.7-1 1.4-1.6 1.2-1.2 3-2.3 5.5-2.3H13v.3c-.4 0-.8-.1-1.1-.1-1.9 0-3.5 1.6-3.5 3.5s.6 2.3 1.6 2.9l-2 4.4.9.4 7.6-16.2-.9-.4Zm-3 12.6c1.7-.2 3-1.7 3-3.5s-.2-1.4-.6-1.9L12.4 16Z" }) });

// node_modules/@wordpress/ui/build-module/stack/stack.mjs
var import_element2 = __toESM(require_element(), 1);
if (typeof document !== "undefined" && !document.head.querySelector("style[data-wp-hash='71d20935c2']")) {
  const style = document.createElement("style");
  style.setAttribute("data-wp-hash", "71d20935c2");
  style.appendChild(document.createTextNode("@layer wp-ui-utilities, wp-ui-components, wp-ui-compositions, wp-ui-overrides;@layer wp-ui-components{._19ce0419607e1896__stack{display:flex}}"));
  document.head.appendChild(style);
}
var style_default2 = { "stack": "_19ce0419607e1896__stack" };
var gapTokens = {
  xs: "var(--wpds-dimension-gap-xs, 4px)",
  sm: "var(--wpds-dimension-gap-sm, 8px)",
  md: "var(--wpds-dimension-gap-md, 12px)",
  lg: "var(--wpds-dimension-gap-lg, 16px)",
  xl: "var(--wpds-dimension-gap-xl, 24px)",
  "2xl": "var(--wpds-dimension-gap-2xl, 32px)",
  "3xl": "var(--wpds-dimension-gap-3xl, 40px)"
};
var Stack = (0, import_element2.forwardRef)(function Stack2({ direction, gap, align, justify, wrap, render: render4, ...props }, ref) {
  const style = {
    gap: gap && gapTokens[gap],
    alignItems: align,
    justifyContent: justify,
    flexDirection: direction,
    flexWrap: wrap
  };
  const element = useRender({
    render: render4,
    ref,
    props: mergeProps(props, { style, className: style_default2.stack })
  });
  return element;
});

// node_modules/@wordpress/dataviews/build-module/constants.mjs
var import_i18n = __toESM(require_i18n(), 1);
var OPERATOR_IS_ANY = "isAny";
var OPERATOR_IS_NONE = "isNone";
var OPERATOR_IS_ALL = "isAll";
var OPERATOR_IS_NOT_ALL = "isNotAll";
var OPERATOR_BETWEEN = "between";
var OPERATOR_IN_THE_PAST = "inThePast";
var OPERATOR_OVER = "over";
var OPERATOR_IS = "is";
var OPERATOR_IS_NOT = "isNot";
var OPERATOR_LESS_THAN = "lessThan";
var OPERATOR_GREATER_THAN = "greaterThan";
var OPERATOR_LESS_THAN_OR_EQUAL = "lessThanOrEqual";
var OPERATOR_GREATER_THAN_OR_EQUAL = "greaterThanOrEqual";
var OPERATOR_BEFORE = "before";
var OPERATOR_AFTER = "after";
var OPERATOR_BEFORE_INC = "beforeInc";
var OPERATOR_AFTER_INC = "afterInc";
var OPERATOR_CONTAINS = "contains";
var OPERATOR_NOT_CONTAINS = "notContains";
var OPERATOR_STARTS_WITH = "startsWith";
var OPERATOR_ON = "on";
var OPERATOR_NOT_ON = "notOn";
var sortLabels = {
  asc: (0, import_i18n.__)("Sort ascending"),
  desc: (0, import_i18n.__)("Sort descending")
};

// node_modules/@wordpress/dataviews/build-module/lock-unlock.mjs
var import_private_apis = __toESM(require_private_apis(), 1);
var { lock, unlock } = (0, import_private_apis.__dangerousOptInToUnstableAPIsOnlyForCoreModules)(
  "I acknowledge private features are not for use in themes or plugins and doing so will break in the next version of WordPress.",
  "@wordpress/dataviews"
);

// node_modules/@wordpress/dataviews/build-module/hooks/use-elements.mjs
var import_element3 = __toESM(require_element(), 1);
var EMPTY_ARRAY2 = [];
function useElements({
  elements,
  getElements
}) {
  const staticElements = Array.isArray(elements) && elements.length > 0 ? elements : EMPTY_ARRAY2;
  const [records, setRecords] = (0, import_element3.useState)(staticElements);
  const [isLoading, setIsLoading] = (0, import_element3.useState)(false);
  (0, import_element3.useEffect)(() => {
    if (!getElements) {
      setRecords(staticElements);
      return;
    }
    let cancelled = false;
    setIsLoading(true);
    getElements().then((fetchedElements) => {
      if (!cancelled) {
        const dynamicElements = Array.isArray(fetchedElements) && fetchedElements.length > 0 ? fetchedElements : staticElements;
        setRecords(dynamicElements);
      }
    }).catch(() => {
      if (!cancelled) {
        setRecords(staticElements);
      }
    }).finally(() => {
      if (!cancelled) {
        setIsLoading(false);
      }
    });
    return () => {
      cancelled = true;
    };
  }, [getElements, staticElements]);
  return {
    elements: records,
    isLoading
  };
}

// node_modules/date-fns/constants.js
var daysInYear = 365.2425;
var maxTime = Math.pow(10, 8) * 24 * 60 * 60 * 1e3;
var minTime = -maxTime;
var millisecondsInWeek = 6048e5;
var millisecondsInDay = 864e5;
var secondsInHour = 3600;
var secondsInDay = secondsInHour * 24;
var secondsInWeek = secondsInDay * 7;
var secondsInYear = secondsInDay * daysInYear;
var secondsInMonth = secondsInYear / 12;
var secondsInQuarter = secondsInMonth * 3;
var constructFromSymbol = Symbol.for("constructDateFrom");

// node_modules/date-fns/constructFrom.js
function constructFrom(date, value) {
  if (typeof date === "function") return date(value);
  if (date && typeof date === "object" && constructFromSymbol in date)
    return date[constructFromSymbol](value);
  if (date instanceof Date) return new date.constructor(value);
  return new Date(value);
}

// node_modules/date-fns/toDate.js
function toDate(argument, context) {
  return constructFrom(context || argument, argument);
}

// node_modules/date-fns/addDays.js
function addDays(date, amount, options) {
  const _date = toDate(date, options?.in);
  if (isNaN(amount)) return constructFrom(options?.in || date, NaN);
  if (!amount) return _date;
  _date.setDate(_date.getDate() + amount);
  return _date;
}

// node_modules/date-fns/addMonths.js
function addMonths(date, amount, options) {
  const _date = toDate(date, options?.in);
  if (isNaN(amount)) return constructFrom(options?.in || date, NaN);
  if (!amount) {
    return _date;
  }
  const dayOfMonth = _date.getDate();
  const endOfDesiredMonth = constructFrom(options?.in || date, _date.getTime());
  endOfDesiredMonth.setMonth(_date.getMonth() + amount + 1, 0);
  const daysInMonth = endOfDesiredMonth.getDate();
  if (dayOfMonth >= daysInMonth) {
    return endOfDesiredMonth;
  } else {
    _date.setFullYear(
      endOfDesiredMonth.getFullYear(),
      endOfDesiredMonth.getMonth(),
      dayOfMonth
    );
    return _date;
  }
}

// node_modules/date-fns/_lib/defaultOptions.js
var defaultOptions = {};
function getDefaultOptions() {
  return defaultOptions;
}

// node_modules/date-fns/startOfWeek.js
function startOfWeek(date, options) {
  const defaultOptions2 = getDefaultOptions();
  const weekStartsOn = options?.weekStartsOn ?? options?.locale?.options?.weekStartsOn ?? defaultOptions2.weekStartsOn ?? defaultOptions2.locale?.options?.weekStartsOn ?? 0;
  const _date = toDate(date, options?.in);
  const day = _date.getDay();
  const diff = (day < weekStartsOn ? 7 : 0) + day - weekStartsOn;
  _date.setDate(_date.getDate() - diff);
  _date.setHours(0, 0, 0, 0);
  return _date;
}

// node_modules/date-fns/startOfISOWeek.js
function startOfISOWeek(date, options) {
  return startOfWeek(date, { ...options, weekStartsOn: 1 });
}

// node_modules/date-fns/getISOWeekYear.js
function getISOWeekYear(date, options) {
  const _date = toDate(date, options?.in);
  const year = _date.getFullYear();
  const fourthOfJanuaryOfNextYear = constructFrom(_date, 0);
  fourthOfJanuaryOfNextYear.setFullYear(year + 1, 0, 4);
  fourthOfJanuaryOfNextYear.setHours(0, 0, 0, 0);
  const startOfNextYear = startOfISOWeek(fourthOfJanuaryOfNextYear);
  const fourthOfJanuaryOfThisYear = constructFrom(_date, 0);
  fourthOfJanuaryOfThisYear.setFullYear(year, 0, 4);
  fourthOfJanuaryOfThisYear.setHours(0, 0, 0, 0);
  const startOfThisYear = startOfISOWeek(fourthOfJanuaryOfThisYear);
  if (_date.getTime() >= startOfNextYear.getTime()) {
    return year + 1;
  } else if (_date.getTime() >= startOfThisYear.getTime()) {
    return year;
  } else {
    return year - 1;
  }
}

// node_modules/date-fns/_lib/getTimezoneOffsetInMilliseconds.js
function getTimezoneOffsetInMilliseconds(date) {
  const _date = toDate(date);
  const utcDate = new Date(
    Date.UTC(
      _date.getFullYear(),
      _date.getMonth(),
      _date.getDate(),
      _date.getHours(),
      _date.getMinutes(),
      _date.getSeconds(),
      _date.getMilliseconds()
    )
  );
  utcDate.setUTCFullYear(_date.getFullYear());
  return +date - +utcDate;
}

// node_modules/date-fns/_lib/normalizeDates.js
function normalizeDates(context, ...dates) {
  const normalize = constructFrom.bind(
    null,
    context || dates.find((date) => typeof date === "object")
  );
  return dates.map(normalize);
}

// node_modules/date-fns/startOfDay.js
function startOfDay(date, options) {
  const _date = toDate(date, options?.in);
  _date.setHours(0, 0, 0, 0);
  return _date;
}

// node_modules/date-fns/differenceInCalendarDays.js
function differenceInCalendarDays(laterDate, earlierDate, options) {
  const [laterDate_, earlierDate_] = normalizeDates(
    options?.in,
    laterDate,
    earlierDate
  );
  const laterStartOfDay = startOfDay(laterDate_);
  const earlierStartOfDay = startOfDay(earlierDate_);
  const laterTimestamp = +laterStartOfDay - getTimezoneOffsetInMilliseconds(laterStartOfDay);
  const earlierTimestamp = +earlierStartOfDay - getTimezoneOffsetInMilliseconds(earlierStartOfDay);
  return Math.round((laterTimestamp - earlierTimestamp) / millisecondsInDay);
}

// node_modules/date-fns/startOfISOWeekYear.js
function startOfISOWeekYear(date, options) {
  const year = getISOWeekYear(date, options);
  const fourthOfJanuary = constructFrom(options?.in || date, 0);
  fourthOfJanuary.setFullYear(year, 0, 4);
  fourthOfJanuary.setHours(0, 0, 0, 0);
  return startOfISOWeek(fourthOfJanuary);
}

// node_modules/date-fns/addWeeks.js
function addWeeks(date, amount, options) {
  return addDays(date, amount * 7, options);
}

// node_modules/date-fns/addYears.js
function addYears(date, amount, options) {
  return addMonths(date, amount * 12, options);
}

// node_modules/date-fns/isDate.js
function isDate(value) {
  return value instanceof Date || typeof value === "object" && Object.prototype.toString.call(value) === "[object Date]";
}

// node_modules/date-fns/isValid.js
function isValid(date) {
  return !(!isDate(date) && typeof date !== "number" || isNaN(+toDate(date)));
}

// node_modules/date-fns/startOfMonth.js
function startOfMonth(date, options) {
  const _date = toDate(date, options?.in);
  _date.setDate(1);
  _date.setHours(0, 0, 0, 0);
  return _date;
}

// node_modules/date-fns/startOfYear.js
function startOfYear(date, options) {
  const date_ = toDate(date, options?.in);
  date_.setFullYear(date_.getFullYear(), 0, 1);
  date_.setHours(0, 0, 0, 0);
  return date_;
}

// node_modules/date-fns/locale/en-US/_lib/formatDistance.js
var formatDistanceLocale = {
  lessThanXSeconds: {
    one: "less than a second",
    other: "less than {{count}} seconds"
  },
  xSeconds: {
    one: "1 second",
    other: "{{count}} seconds"
  },
  halfAMinute: "half a minute",
  lessThanXMinutes: {
    one: "less than a minute",
    other: "less than {{count}} minutes"
  },
  xMinutes: {
    one: "1 minute",
    other: "{{count}} minutes"
  },
  aboutXHours: {
    one: "about 1 hour",
    other: "about {{count}} hours"
  },
  xHours: {
    one: "1 hour",
    other: "{{count}} hours"
  },
  xDays: {
    one: "1 day",
    other: "{{count}} days"
  },
  aboutXWeeks: {
    one: "about 1 week",
    other: "about {{count}} weeks"
  },
  xWeeks: {
    one: "1 week",
    other: "{{count}} weeks"
  },
  aboutXMonths: {
    one: "about 1 month",
    other: "about {{count}} months"
  },
  xMonths: {
    one: "1 month",
    other: "{{count}} months"
  },
  aboutXYears: {
    one: "about 1 year",
    other: "about {{count}} years"
  },
  xYears: {
    one: "1 year",
    other: "{{count}} years"
  },
  overXYears: {
    one: "over 1 year",
    other: "over {{count}} years"
  },
  almostXYears: {
    one: "almost 1 year",
    other: "almost {{count}} years"
  }
};
var formatDistance = (token, count, options) => {
  let result;
  const tokenValue = formatDistanceLocale[token];
  if (typeof tokenValue === "string") {
    result = tokenValue;
  } else if (count === 1) {
    result = tokenValue.one;
  } else {
    result = tokenValue.other.replace("{{count}}", count.toString());
  }
  if (options?.addSuffix) {
    if (options.comparison && options.comparison > 0) {
      return "in " + result;
    } else {
      return result + " ago";
    }
  }
  return result;
};

// node_modules/date-fns/locale/_lib/buildFormatLongFn.js
function buildFormatLongFn(args) {
  return (options = {}) => {
    const width = options.width ? String(options.width) : args.defaultWidth;
    const format6 = args.formats[width] || args.formats[args.defaultWidth];
    return format6;
  };
}

// node_modules/date-fns/locale/en-US/_lib/formatLong.js
var dateFormats = {
  full: "EEEE, MMMM do, y",
  long: "MMMM do, y",
  medium: "MMM d, y",
  short: "MM/dd/yyyy"
};
var timeFormats = {
  full: "h:mm:ss a zzzz",
  long: "h:mm:ss a z",
  medium: "h:mm:ss a",
  short: "h:mm a"
};
var dateTimeFormats = {
  full: "{{date}} 'at' {{time}}",
  long: "{{date}} 'at' {{time}}",
  medium: "{{date}}, {{time}}",
  short: "{{date}}, {{time}}"
};
var formatLong = {
  date: buildFormatLongFn({
    formats: dateFormats,
    defaultWidth: "full"
  }),
  time: buildFormatLongFn({
    formats: timeFormats,
    defaultWidth: "full"
  }),
  dateTime: buildFormatLongFn({
    formats: dateTimeFormats,
    defaultWidth: "full"
  })
};

// node_modules/date-fns/locale/en-US/_lib/formatRelative.js
var formatRelativeLocale = {
  lastWeek: "'last' eeee 'at' p",
  yesterday: "'yesterday at' p",
  today: "'today at' p",
  tomorrow: "'tomorrow at' p",
  nextWeek: "eeee 'at' p",
  other: "P"
};
var formatRelative = (token, _date, _baseDate, _options) => formatRelativeLocale[token];

// node_modules/date-fns/locale/_lib/buildLocalizeFn.js
function buildLocalizeFn(args) {
  return (value, options) => {
    const context = options?.context ? String(options.context) : "standalone";
    let valuesArray;
    if (context === "formatting" && args.formattingValues) {
      const defaultWidth = args.defaultFormattingWidth || args.defaultWidth;
      const width = options?.width ? String(options.width) : defaultWidth;
      valuesArray = args.formattingValues[width] || args.formattingValues[defaultWidth];
    } else {
      const defaultWidth = args.defaultWidth;
      const width = options?.width ? String(options.width) : args.defaultWidth;
      valuesArray = args.values[width] || args.values[defaultWidth];
    }
    const index = args.argumentCallback ? args.argumentCallback(value) : value;
    return valuesArray[index];
  };
}

// node_modules/date-fns/locale/en-US/_lib/localize.js
var eraValues = {
  narrow: ["B", "A"],
  abbreviated: ["BC", "AD"],
  wide: ["Before Christ", "Anno Domini"]
};
var quarterValues = {
  narrow: ["1", "2", "3", "4"],
  abbreviated: ["Q1", "Q2", "Q3", "Q4"],
  wide: ["1st quarter", "2nd quarter", "3rd quarter", "4th quarter"]
};
var monthValues = {
  narrow: ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"],
  abbreviated: [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec"
  ],
  wide: [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December"
  ]
};
var dayValues = {
  narrow: ["S", "M", "T", "W", "T", "F", "S"],
  short: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
  abbreviated: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
  wide: [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday"
  ]
};
var dayPeriodValues = {
  narrow: {
    am: "a",
    pm: "p",
    midnight: "mi",
    noon: "n",
    morning: "morning",
    afternoon: "afternoon",
    evening: "evening",
    night: "night"
  },
  abbreviated: {
    am: "AM",
    pm: "PM",
    midnight: "midnight",
    noon: "noon",
    morning: "morning",
    afternoon: "afternoon",
    evening: "evening",
    night: "night"
  },
  wide: {
    am: "a.m.",
    pm: "p.m.",
    midnight: "midnight",
    noon: "noon",
    morning: "morning",
    afternoon: "afternoon",
    evening: "evening",
    night: "night"
  }
};
var formattingDayPeriodValues = {
  narrow: {
    am: "a",
    pm: "p",
    midnight: "mi",
    noon: "n",
    morning: "in the morning",
    afternoon: "in the afternoon",
    evening: "in the evening",
    night: "at night"
  },
  abbreviated: {
    am: "AM",
    pm: "PM",
    midnight: "midnight",
    noon: "noon",
    morning: "in the morning",
    afternoon: "in the afternoon",
    evening: "in the evening",
    night: "at night"
  },
  wide: {
    am: "a.m.",
    pm: "p.m.",
    midnight: "midnight",
    noon: "noon",
    morning: "in the morning",
    afternoon: "in the afternoon",
    evening: "in the evening",
    night: "at night"
  }
};
var ordinalNumber = (dirtyNumber, _options) => {
  const number = Number(dirtyNumber);
  const rem100 = number % 100;
  if (rem100 > 20 || rem100 < 10) {
    switch (rem100 % 10) {
      case 1:
        return number + "st";
      case 2:
        return number + "nd";
      case 3:
        return number + "rd";
    }
  }
  return number + "th";
};
var localize = {
  ordinalNumber,
  era: buildLocalizeFn({
    values: eraValues,
    defaultWidth: "wide"
  }),
  quarter: buildLocalizeFn({
    values: quarterValues,
    defaultWidth: "wide",
    argumentCallback: (quarter) => quarter - 1
  }),
  month: buildLocalizeFn({
    values: monthValues,
    defaultWidth: "wide"
  }),
  day: buildLocalizeFn({
    values: dayValues,
    defaultWidth: "wide"
  }),
  dayPeriod: buildLocalizeFn({
    values: dayPeriodValues,
    defaultWidth: "wide",
    formattingValues: formattingDayPeriodValues,
    defaultFormattingWidth: "wide"
  })
};

// node_modules/date-fns/locale/_lib/buildMatchFn.js
function buildMatchFn(args) {
  return (string, options = {}) => {
    const width = options.width;
    const matchPattern = width && args.matchPatterns[width] || args.matchPatterns[args.defaultMatchWidth];
    const matchResult = string.match(matchPattern);
    if (!matchResult) {
      return null;
    }
    const matchedString = matchResult[0];
    const parsePatterns = width && args.parsePatterns[width] || args.parsePatterns[args.defaultParseWidth];
    const key = Array.isArray(parsePatterns) ? findIndex(parsePatterns, (pattern) => pattern.test(matchedString)) : (
      // [TODO] -- I challenge you to fix the type
      findKey(parsePatterns, (pattern) => pattern.test(matchedString))
    );
    let value;
    value = args.valueCallback ? args.valueCallback(key) : key;
    value = options.valueCallback ? (
      // [TODO] -- I challenge you to fix the type
      options.valueCallback(value)
    ) : value;
    const rest = string.slice(matchedString.length);
    return { value, rest };
  };
}
function findKey(object, predicate) {
  for (const key in object) {
    if (Object.prototype.hasOwnProperty.call(object, key) && predicate(object[key])) {
      return key;
    }
  }
  return void 0;
}
function findIndex(array, predicate) {
  for (let key = 0; key < array.length; key++) {
    if (predicate(array[key])) {
      return key;
    }
  }
  return void 0;
}

// node_modules/date-fns/locale/_lib/buildMatchPatternFn.js
function buildMatchPatternFn(args) {
  return (string, options = {}) => {
    const matchResult = string.match(args.matchPattern);
    if (!matchResult) return null;
    const matchedString = matchResult[0];
    const parseResult = string.match(args.parsePattern);
    if (!parseResult) return null;
    let value = args.valueCallback ? args.valueCallback(parseResult[0]) : parseResult[0];
    value = options.valueCallback ? options.valueCallback(value) : value;
    const rest = string.slice(matchedString.length);
    return { value, rest };
  };
}

// node_modules/date-fns/locale/en-US/_lib/match.js
var matchOrdinalNumberPattern = /^(\d+)(th|st|nd|rd)?/i;
var parseOrdinalNumberPattern = /\d+/i;
var matchEraPatterns = {
  narrow: /^(b|a)/i,
  abbreviated: /^(b\.?\s?c\.?|b\.?\s?c\.?\s?e\.?|a\.?\s?d\.?|c\.?\s?e\.?)/i,
  wide: /^(before christ|before common era|anno domini|common era)/i
};
var parseEraPatterns = {
  any: [/^b/i, /^(a|c)/i]
};
var matchQuarterPatterns = {
  narrow: /^[1234]/i,
  abbreviated: /^q[1234]/i,
  wide: /^[1234](th|st|nd|rd)? quarter/i
};
var parseQuarterPatterns = {
  any: [/1/i, /2/i, /3/i, /4/i]
};
var matchMonthPatterns = {
  narrow: /^[jfmasond]/i,
  abbreviated: /^(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)/i,
  wide: /^(january|february|march|april|may|june|july|august|september|october|november|december)/i
};
var parseMonthPatterns = {
  narrow: [
    /^j/i,
    /^f/i,
    /^m/i,
    /^a/i,
    /^m/i,
    /^j/i,
    /^j/i,
    /^a/i,
    /^s/i,
    /^o/i,
    /^n/i,
    /^d/i
  ],
  any: [
    /^ja/i,
    /^f/i,
    /^mar/i,
    /^ap/i,
    /^may/i,
    /^jun/i,
    /^jul/i,
    /^au/i,
    /^s/i,
    /^o/i,
    /^n/i,
    /^d/i
  ]
};
var matchDayPatterns = {
  narrow: /^[smtwf]/i,
  short: /^(su|mo|tu|we|th|fr|sa)/i,
  abbreviated: /^(sun|mon|tue|wed|thu|fri|sat)/i,
  wide: /^(sunday|monday|tuesday|wednesday|thursday|friday|saturday)/i
};
var parseDayPatterns = {
  narrow: [/^s/i, /^m/i, /^t/i, /^w/i, /^t/i, /^f/i, /^s/i],
  any: [/^su/i, /^m/i, /^tu/i, /^w/i, /^th/i, /^f/i, /^sa/i]
};
var matchDayPeriodPatterns = {
  narrow: /^(a|p|mi|n|(in the|at) (morning|afternoon|evening|night))/i,
  any: /^([ap]\.?\s?m\.?|midnight|noon|(in the|at) (morning|afternoon|evening|night))/i
};
var parseDayPeriodPatterns = {
  any: {
    am: /^a/i,
    pm: /^p/i,
    midnight: /^mi/i,
    noon: /^no/i,
    morning: /morning/i,
    afternoon: /afternoon/i,
    evening: /evening/i,
    night: /night/i
  }
};
var match = {
  ordinalNumber: buildMatchPatternFn({
    matchPattern: matchOrdinalNumberPattern,
    parsePattern: parseOrdinalNumberPattern,
    valueCallback: (value) => parseInt(value, 10)
  }),
  era: buildMatchFn({
    matchPatterns: matchEraPatterns,
    defaultMatchWidth: "wide",
    parsePatterns: parseEraPatterns,
    defaultParseWidth: "any"
  }),
  quarter: buildMatchFn({
    matchPatterns: matchQuarterPatterns,
    defaultMatchWidth: "wide",
    parsePatterns: parseQuarterPatterns,
    defaultParseWidth: "any",
    valueCallback: (index) => index + 1
  }),
  month: buildMatchFn({
    matchPatterns: matchMonthPatterns,
    defaultMatchWidth: "wide",
    parsePatterns: parseMonthPatterns,
    defaultParseWidth: "any"
  }),
  day: buildMatchFn({
    matchPatterns: matchDayPatterns,
    defaultMatchWidth: "wide",
    parsePatterns: parseDayPatterns,
    defaultParseWidth: "any"
  }),
  dayPeriod: buildMatchFn({
    matchPatterns: matchDayPeriodPatterns,
    defaultMatchWidth: "any",
    parsePatterns: parseDayPeriodPatterns,
    defaultParseWidth: "any"
  })
};

// node_modules/date-fns/locale/en-US.js
var enUS = {
  code: "en-US",
  formatDistance,
  formatLong,
  formatRelative,
  localize,
  match,
  options: {
    weekStartsOn: 0,
    firstWeekContainsDate: 1
  }
};

// node_modules/date-fns/getDayOfYear.js
function getDayOfYear(date, options) {
  const _date = toDate(date, options?.in);
  const diff = differenceInCalendarDays(_date, startOfYear(_date));
  const dayOfYear = diff + 1;
  return dayOfYear;
}

// node_modules/date-fns/getISOWeek.js
function getISOWeek(date, options) {
  const _date = toDate(date, options?.in);
  const diff = +startOfISOWeek(_date) - +startOfISOWeekYear(_date);
  return Math.round(diff / millisecondsInWeek) + 1;
}

// node_modules/date-fns/getWeekYear.js
function getWeekYear(date, options) {
  const _date = toDate(date, options?.in);
  const year = _date.getFullYear();
  const defaultOptions2 = getDefaultOptions();
  const firstWeekContainsDate = options?.firstWeekContainsDate ?? options?.locale?.options?.firstWeekContainsDate ?? defaultOptions2.firstWeekContainsDate ?? defaultOptions2.locale?.options?.firstWeekContainsDate ?? 1;
  const firstWeekOfNextYear = constructFrom(options?.in || date, 0);
  firstWeekOfNextYear.setFullYear(year + 1, 0, firstWeekContainsDate);
  firstWeekOfNextYear.setHours(0, 0, 0, 0);
  const startOfNextYear = startOfWeek(firstWeekOfNextYear, options);
  const firstWeekOfThisYear = constructFrom(options?.in || date, 0);
  firstWeekOfThisYear.setFullYear(year, 0, firstWeekContainsDate);
  firstWeekOfThisYear.setHours(0, 0, 0, 0);
  const startOfThisYear = startOfWeek(firstWeekOfThisYear, options);
  if (+_date >= +startOfNextYear) {
    return year + 1;
  } else if (+_date >= +startOfThisYear) {
    return year;
  } else {
    return year - 1;
  }
}

// node_modules/date-fns/startOfWeekYear.js
function startOfWeekYear(date, options) {
  const defaultOptions2 = getDefaultOptions();
  const firstWeekContainsDate = options?.firstWeekContainsDate ?? options?.locale?.options?.firstWeekContainsDate ?? defaultOptions2.firstWeekContainsDate ?? defaultOptions2.locale?.options?.firstWeekContainsDate ?? 1;
  const year = getWeekYear(date, options);
  const firstWeek = constructFrom(options?.in || date, 0);
  firstWeek.setFullYear(year, 0, firstWeekContainsDate);
  firstWeek.setHours(0, 0, 0, 0);
  const _date = startOfWeek(firstWeek, options);
  return _date;
}

// node_modules/date-fns/getWeek.js
function getWeek(date, options) {
  const _date = toDate(date, options?.in);
  const diff = +startOfWeek(_date, options) - +startOfWeekYear(_date, options);
  return Math.round(diff / millisecondsInWeek) + 1;
}

// node_modules/date-fns/_lib/addLeadingZeros.js
function addLeadingZeros(number, targetLength) {
  const sign = number < 0 ? "-" : "";
  const output = Math.abs(number).toString().padStart(targetLength, "0");
  return sign + output;
}

// node_modules/date-fns/_lib/format/lightFormatters.js
var lightFormatters = {
  // Year
  y(date, token) {
    const signedYear = date.getFullYear();
    const year = signedYear > 0 ? signedYear : 1 - signedYear;
    return addLeadingZeros(token === "yy" ? year % 100 : year, token.length);
  },
  // Month
  M(date, token) {
    const month = date.getMonth();
    return token === "M" ? String(month + 1) : addLeadingZeros(month + 1, 2);
  },
  // Day of the month
  d(date, token) {
    return addLeadingZeros(date.getDate(), token.length);
  },
  // AM or PM
  a(date, token) {
    const dayPeriodEnumValue = date.getHours() / 12 >= 1 ? "pm" : "am";
    switch (token) {
      case "a":
      case "aa":
        return dayPeriodEnumValue.toUpperCase();
      case "aaa":
        return dayPeriodEnumValue;
      case "aaaaa":
        return dayPeriodEnumValue[0];
      case "aaaa":
      default:
        return dayPeriodEnumValue === "am" ? "a.m." : "p.m.";
    }
  },
  // Hour [1-12]
  h(date, token) {
    return addLeadingZeros(date.getHours() % 12 || 12, token.length);
  },
  // Hour [0-23]
  H(date, token) {
    return addLeadingZeros(date.getHours(), token.length);
  },
  // Minute
  m(date, token) {
    return addLeadingZeros(date.getMinutes(), token.length);
  },
  // Second
  s(date, token) {
    return addLeadingZeros(date.getSeconds(), token.length);
  },
  // Fraction of second
  S(date, token) {
    const numberOfDigits = token.length;
    const milliseconds = date.getMilliseconds();
    const fractionalSeconds = Math.trunc(
      milliseconds * Math.pow(10, numberOfDigits - 3)
    );
    return addLeadingZeros(fractionalSeconds, token.length);
  }
};

// node_modules/date-fns/_lib/format/formatters.js
var dayPeriodEnum = {
  am: "am",
  pm: "pm",
  midnight: "midnight",
  noon: "noon",
  morning: "morning",
  afternoon: "afternoon",
  evening: "evening",
  night: "night"
};
var formatters = {
  // Era
  G: function(date, token, localize2) {
    const era = date.getFullYear() > 0 ? 1 : 0;
    switch (token) {
      // AD, BC
      case "G":
      case "GG":
      case "GGG":
        return localize2.era(era, { width: "abbreviated" });
      // A, B
      case "GGGGG":
        return localize2.era(era, { width: "narrow" });
      // Anno Domini, Before Christ
      case "GGGG":
      default:
        return localize2.era(era, { width: "wide" });
    }
  },
  // Year
  y: function(date, token, localize2) {
    if (token === "yo") {
      const signedYear = date.getFullYear();
      const year = signedYear > 0 ? signedYear : 1 - signedYear;
      return localize2.ordinalNumber(year, { unit: "year" });
    }
    return lightFormatters.y(date, token);
  },
  // Local week-numbering year
  Y: function(date, token, localize2, options) {
    const signedWeekYear = getWeekYear(date, options);
    const weekYear = signedWeekYear > 0 ? signedWeekYear : 1 - signedWeekYear;
    if (token === "YY") {
      const twoDigitYear = weekYear % 100;
      return addLeadingZeros(twoDigitYear, 2);
    }
    if (token === "Yo") {
      return localize2.ordinalNumber(weekYear, { unit: "year" });
    }
    return addLeadingZeros(weekYear, token.length);
  },
  // ISO week-numbering year
  R: function(date, token) {
    const isoWeekYear = getISOWeekYear(date);
    return addLeadingZeros(isoWeekYear, token.length);
  },
  // Extended year. This is a single number designating the year of this calendar system.
  // The main difference between `y` and `u` localizers are B.C. years:
  // | Year | `y` | `u` |
  // |------|-----|-----|
  // | AC 1 |   1 |   1 |
  // | BC 1 |   1 |   0 |
  // | BC 2 |   2 |  -1 |
  // Also `yy` always returns the last two digits of a year,
  // while `uu` pads single digit years to 2 characters and returns other years unchanged.
  u: function(date, token) {
    const year = date.getFullYear();
    return addLeadingZeros(year, token.length);
  },
  // Quarter
  Q: function(date, token, localize2) {
    const quarter = Math.ceil((date.getMonth() + 1) / 3);
    switch (token) {
      // 1, 2, 3, 4
      case "Q":
        return String(quarter);
      // 01, 02, 03, 04
      case "QQ":
        return addLeadingZeros(quarter, 2);
      // 1st, 2nd, 3rd, 4th
      case "Qo":
        return localize2.ordinalNumber(quarter, { unit: "quarter" });
      // Q1, Q2, Q3, Q4
      case "QQQ":
        return localize2.quarter(quarter, {
          width: "abbreviated",
          context: "formatting"
        });
      // 1, 2, 3, 4 (narrow quarter; could be not numerical)
      case "QQQQQ":
        return localize2.quarter(quarter, {
          width: "narrow",
          context: "formatting"
        });
      // 1st quarter, 2nd quarter, ...
      case "QQQQ":
      default:
        return localize2.quarter(quarter, {
          width: "wide",
          context: "formatting"
        });
    }
  },
  // Stand-alone quarter
  q: function(date, token, localize2) {
    const quarter = Math.ceil((date.getMonth() + 1) / 3);
    switch (token) {
      // 1, 2, 3, 4
      case "q":
        return String(quarter);
      // 01, 02, 03, 04
      case "qq":
        return addLeadingZeros(quarter, 2);
      // 1st, 2nd, 3rd, 4th
      case "qo":
        return localize2.ordinalNumber(quarter, { unit: "quarter" });
      // Q1, Q2, Q3, Q4
      case "qqq":
        return localize2.quarter(quarter, {
          width: "abbreviated",
          context: "standalone"
        });
      // 1, 2, 3, 4 (narrow quarter; could be not numerical)
      case "qqqqq":
        return localize2.quarter(quarter, {
          width: "narrow",
          context: "standalone"
        });
      // 1st quarter, 2nd quarter, ...
      case "qqqq":
      default:
        return localize2.quarter(quarter, {
          width: "wide",
          context: "standalone"
        });
    }
  },
  // Month
  M: function(date, token, localize2) {
    const month = date.getMonth();
    switch (token) {
      case "M":
      case "MM":
        return lightFormatters.M(date, token);
      // 1st, 2nd, ..., 12th
      case "Mo":
        return localize2.ordinalNumber(month + 1, { unit: "month" });
      // Jan, Feb, ..., Dec
      case "MMM":
        return localize2.month(month, {
          width: "abbreviated",
          context: "formatting"
        });
      // J, F, ..., D
      case "MMMMM":
        return localize2.month(month, {
          width: "narrow",
          context: "formatting"
        });
      // January, February, ..., December
      case "MMMM":
      default:
        return localize2.month(month, { width: "wide", context: "formatting" });
    }
  },
  // Stand-alone month
  L: function(date, token, localize2) {
    const month = date.getMonth();
    switch (token) {
      // 1, 2, ..., 12
      case "L":
        return String(month + 1);
      // 01, 02, ..., 12
      case "LL":
        return addLeadingZeros(month + 1, 2);
      // 1st, 2nd, ..., 12th
      case "Lo":
        return localize2.ordinalNumber(month + 1, { unit: "month" });
      // Jan, Feb, ..., Dec
      case "LLL":
        return localize2.month(month, {
          width: "abbreviated",
          context: "standalone"
        });
      // J, F, ..., D
      case "LLLLL":
        return localize2.month(month, {
          width: "narrow",
          context: "standalone"
        });
      // January, February, ..., December
      case "LLLL":
      default:
        return localize2.month(month, { width: "wide", context: "standalone" });
    }
  },
  // Local week of year
  w: function(date, token, localize2, options) {
    const week = getWeek(date, options);
    if (token === "wo") {
      return localize2.ordinalNumber(week, { unit: "week" });
    }
    return addLeadingZeros(week, token.length);
  },
  // ISO week of year
  I: function(date, token, localize2) {
    const isoWeek = getISOWeek(date);
    if (token === "Io") {
      return localize2.ordinalNumber(isoWeek, { unit: "week" });
    }
    return addLeadingZeros(isoWeek, token.length);
  },
  // Day of the month
  d: function(date, token, localize2) {
    if (token === "do") {
      return localize2.ordinalNumber(date.getDate(), { unit: "date" });
    }
    return lightFormatters.d(date, token);
  },
  // Day of year
  D: function(date, token, localize2) {
    const dayOfYear = getDayOfYear(date);
    if (token === "Do") {
      return localize2.ordinalNumber(dayOfYear, { unit: "dayOfYear" });
    }
    return addLeadingZeros(dayOfYear, token.length);
  },
  // Day of week
  E: function(date, token, localize2) {
    const dayOfWeek = date.getDay();
    switch (token) {
      // Tue
      case "E":
      case "EE":
      case "EEE":
        return localize2.day(dayOfWeek, {
          width: "abbreviated",
          context: "formatting"
        });
      // T
      case "EEEEE":
        return localize2.day(dayOfWeek, {
          width: "narrow",
          context: "formatting"
        });
      // Tu
      case "EEEEEE":
        return localize2.day(dayOfWeek, {
          width: "short",
          context: "formatting"
        });
      // Tuesday
      case "EEEE":
      default:
        return localize2.day(dayOfWeek, {
          width: "wide",
          context: "formatting"
        });
    }
  },
  // Local day of week
  e: function(date, token, localize2, options) {
    const dayOfWeek = date.getDay();
    const localDayOfWeek = (dayOfWeek - options.weekStartsOn + 8) % 7 || 7;
    switch (token) {
      // Numerical value (Nth day of week with current locale or weekStartsOn)
      case "e":
        return String(localDayOfWeek);
      // Padded numerical value
      case "ee":
        return addLeadingZeros(localDayOfWeek, 2);
      // 1st, 2nd, ..., 7th
      case "eo":
        return localize2.ordinalNumber(localDayOfWeek, { unit: "day" });
      case "eee":
        return localize2.day(dayOfWeek, {
          width: "abbreviated",
          context: "formatting"
        });
      // T
      case "eeeee":
        return localize2.day(dayOfWeek, {
          width: "narrow",
          context: "formatting"
        });
      // Tu
      case "eeeeee":
        return localize2.day(dayOfWeek, {
          width: "short",
          context: "formatting"
        });
      // Tuesday
      case "eeee":
      default:
        return localize2.day(dayOfWeek, {
          width: "wide",
          context: "formatting"
        });
    }
  },
  // Stand-alone local day of week
  c: function(date, token, localize2, options) {
    const dayOfWeek = date.getDay();
    const localDayOfWeek = (dayOfWeek - options.weekStartsOn + 8) % 7 || 7;
    switch (token) {
      // Numerical value (same as in `e`)
      case "c":
        return String(localDayOfWeek);
      // Padded numerical value
      case "cc":
        return addLeadingZeros(localDayOfWeek, token.length);
      // 1st, 2nd, ..., 7th
      case "co":
        return localize2.ordinalNumber(localDayOfWeek, { unit: "day" });
      case "ccc":
        return localize2.day(dayOfWeek, {
          width: "abbreviated",
          context: "standalone"
        });
      // T
      case "ccccc":
        return localize2.day(dayOfWeek, {
          width: "narrow",
          context: "standalone"
        });
      // Tu
      case "cccccc":
        return localize2.day(dayOfWeek, {
          width: "short",
          context: "standalone"
        });
      // Tuesday
      case "cccc":
      default:
        return localize2.day(dayOfWeek, {
          width: "wide",
          context: "standalone"
        });
    }
  },
  // ISO day of week
  i: function(date, token, localize2) {
    const dayOfWeek = date.getDay();
    const isoDayOfWeek = dayOfWeek === 0 ? 7 : dayOfWeek;
    switch (token) {
      // 2
      case "i":
        return String(isoDayOfWeek);
      // 02
      case "ii":
        return addLeadingZeros(isoDayOfWeek, token.length);
      // 2nd
      case "io":
        return localize2.ordinalNumber(isoDayOfWeek, { unit: "day" });
      // Tue
      case "iii":
        return localize2.day(dayOfWeek, {
          width: "abbreviated",
          context: "formatting"
        });
      // T
      case "iiiii":
        return localize2.day(dayOfWeek, {
          width: "narrow",
          context: "formatting"
        });
      // Tu
      case "iiiiii":
        return localize2.day(dayOfWeek, {
          width: "short",
          context: "formatting"
        });
      // Tuesday
      case "iiii":
      default:
        return localize2.day(dayOfWeek, {
          width: "wide",
          context: "formatting"
        });
    }
  },
  // AM or PM
  a: function(date, token, localize2) {
    const hours = date.getHours();
    const dayPeriodEnumValue = hours / 12 >= 1 ? "pm" : "am";
    switch (token) {
      case "a":
      case "aa":
        return localize2.dayPeriod(dayPeriodEnumValue, {
          width: "abbreviated",
          context: "formatting"
        });
      case "aaa":
        return localize2.dayPeriod(dayPeriodEnumValue, {
          width: "abbreviated",
          context: "formatting"
        }).toLowerCase();
      case "aaaaa":
        return localize2.dayPeriod(dayPeriodEnumValue, {
          width: "narrow",
          context: "formatting"
        });
      case "aaaa":
      default:
        return localize2.dayPeriod(dayPeriodEnumValue, {
          width: "wide",
          context: "formatting"
        });
    }
  },
  // AM, PM, midnight, noon
  b: function(date, token, localize2) {
    const hours = date.getHours();
    let dayPeriodEnumValue;
    if (hours === 12) {
      dayPeriodEnumValue = dayPeriodEnum.noon;
    } else if (hours === 0) {
      dayPeriodEnumValue = dayPeriodEnum.midnight;
    } else {
      dayPeriodEnumValue = hours / 12 >= 1 ? "pm" : "am";
    }
    switch (token) {
      case "b":
      case "bb":
        return localize2.dayPeriod(dayPeriodEnumValue, {
          width: "abbreviated",
          context: "formatting"
        });
      case "bbb":
        return localize2.dayPeriod(dayPeriodEnumValue, {
          width: "abbreviated",
          context: "formatting"
        }).toLowerCase();
      case "bbbbb":
        return localize2.dayPeriod(dayPeriodEnumValue, {
          width: "narrow",
          context: "formatting"
        });
      case "bbbb":
      default:
        return localize2.dayPeriod(dayPeriodEnumValue, {
          width: "wide",
          context: "formatting"
        });
    }
  },
  // in the morning, in the afternoon, in the evening, at night
  B: function(date, token, localize2) {
    const hours = date.getHours();
    let dayPeriodEnumValue;
    if (hours >= 17) {
      dayPeriodEnumValue = dayPeriodEnum.evening;
    } else if (hours >= 12) {
      dayPeriodEnumValue = dayPeriodEnum.afternoon;
    } else if (hours >= 4) {
      dayPeriodEnumValue = dayPeriodEnum.morning;
    } else {
      dayPeriodEnumValue = dayPeriodEnum.night;
    }
    switch (token) {
      case "B":
      case "BB":
      case "BBB":
        return localize2.dayPeriod(dayPeriodEnumValue, {
          width: "abbreviated",
          context: "formatting"
        });
      case "BBBBB":
        return localize2.dayPeriod(dayPeriodEnumValue, {
          width: "narrow",
          context: "formatting"
        });
      case "BBBB":
      default:
        return localize2.dayPeriod(dayPeriodEnumValue, {
          width: "wide",
          context: "formatting"
        });
    }
  },
  // Hour [1-12]
  h: function(date, token, localize2) {
    if (token === "ho") {
      let hours = date.getHours() % 12;
      if (hours === 0) hours = 12;
      return localize2.ordinalNumber(hours, { unit: "hour" });
    }
    return lightFormatters.h(date, token);
  },
  // Hour [0-23]
  H: function(date, token, localize2) {
    if (token === "Ho") {
      return localize2.ordinalNumber(date.getHours(), { unit: "hour" });
    }
    return lightFormatters.H(date, token);
  },
  // Hour [0-11]
  K: function(date, token, localize2) {
    const hours = date.getHours() % 12;
    if (token === "Ko") {
      return localize2.ordinalNumber(hours, { unit: "hour" });
    }
    return addLeadingZeros(hours, token.length);
  },
  // Hour [1-24]
  k: function(date, token, localize2) {
    let hours = date.getHours();
    if (hours === 0) hours = 24;
    if (token === "ko") {
      return localize2.ordinalNumber(hours, { unit: "hour" });
    }
    return addLeadingZeros(hours, token.length);
  },
  // Minute
  m: function(date, token, localize2) {
    if (token === "mo") {
      return localize2.ordinalNumber(date.getMinutes(), { unit: "minute" });
    }
    return lightFormatters.m(date, token);
  },
  // Second
  s: function(date, token, localize2) {
    if (token === "so") {
      return localize2.ordinalNumber(date.getSeconds(), { unit: "second" });
    }
    return lightFormatters.s(date, token);
  },
  // Fraction of second
  S: function(date, token) {
    return lightFormatters.S(date, token);
  },
  // Timezone (ISO-8601. If offset is 0, output is always `'Z'`)
  X: function(date, token, _localize) {
    const timezoneOffset = date.getTimezoneOffset();
    if (timezoneOffset === 0) {
      return "Z";
    }
    switch (token) {
      // Hours and optional minutes
      case "X":
        return formatTimezoneWithOptionalMinutes(timezoneOffset);
      // Hours, minutes and optional seconds without `:` delimiter
      // Note: neither ISO-8601 nor JavaScript supports seconds in timezone offsets
      // so this token always has the same output as `XX`
      case "XXXX":
      case "XX":
        return formatTimezone(timezoneOffset);
      // Hours, minutes and optional seconds with `:` delimiter
      // Note: neither ISO-8601 nor JavaScript supports seconds in timezone offsets
      // so this token always has the same output as `XXX`
      case "XXXXX":
      case "XXX":
      // Hours and minutes with `:` delimiter
      default:
        return formatTimezone(timezoneOffset, ":");
    }
  },
  // Timezone (ISO-8601. If offset is 0, output is `'+00:00'` or equivalent)
  x: function(date, token, _localize) {
    const timezoneOffset = date.getTimezoneOffset();
    switch (token) {
      // Hours and optional minutes
      case "x":
        return formatTimezoneWithOptionalMinutes(timezoneOffset);
      // Hours, minutes and optional seconds without `:` delimiter
      // Note: neither ISO-8601 nor JavaScript supports seconds in timezone offsets
      // so this token always has the same output as `xx`
      case "xxxx":
      case "xx":
        return formatTimezone(timezoneOffset);
      // Hours, minutes and optional seconds with `:` delimiter
      // Note: neither ISO-8601 nor JavaScript supports seconds in timezone offsets
      // so this token always has the same output as `xxx`
      case "xxxxx":
      case "xxx":
      // Hours and minutes with `:` delimiter
      default:
        return formatTimezone(timezoneOffset, ":");
    }
  },
  // Timezone (GMT)
  O: function(date, token, _localize) {
    const timezoneOffset = date.getTimezoneOffset();
    switch (token) {
      // Short
      case "O":
      case "OO":
      case "OOO":
        return "GMT" + formatTimezoneShort(timezoneOffset, ":");
      // Long
      case "OOOO":
      default:
        return "GMT" + formatTimezone(timezoneOffset, ":");
    }
  },
  // Timezone (specific non-location)
  z: function(date, token, _localize) {
    const timezoneOffset = date.getTimezoneOffset();
    switch (token) {
      // Short
      case "z":
      case "zz":
      case "zzz":
        return "GMT" + formatTimezoneShort(timezoneOffset, ":");
      // Long
      case "zzzz":
      default:
        return "GMT" + formatTimezone(timezoneOffset, ":");
    }
  },
  // Seconds timestamp
  t: function(date, token, _localize) {
    const timestamp = Math.trunc(+date / 1e3);
    return addLeadingZeros(timestamp, token.length);
  },
  // Milliseconds timestamp
  T: function(date, token, _localize) {
    return addLeadingZeros(+date, token.length);
  }
};
function formatTimezoneShort(offset, delimiter = "") {
  const sign = offset > 0 ? "-" : "+";
  const absOffset = Math.abs(offset);
  const hours = Math.trunc(absOffset / 60);
  const minutes = absOffset % 60;
  if (minutes === 0) {
    return sign + String(hours);
  }
  return sign + String(hours) + delimiter + addLeadingZeros(minutes, 2);
}
function formatTimezoneWithOptionalMinutes(offset, delimiter) {
  if (offset % 60 === 0) {
    const sign = offset > 0 ? "-" : "+";
    return sign + addLeadingZeros(Math.abs(offset) / 60, 2);
  }
  return formatTimezone(offset, delimiter);
}
function formatTimezone(offset, delimiter = "") {
  const sign = offset > 0 ? "-" : "+";
  const absOffset = Math.abs(offset);
  const hours = addLeadingZeros(Math.trunc(absOffset / 60), 2);
  const minutes = addLeadingZeros(absOffset % 60, 2);
  return sign + hours + delimiter + minutes;
}

// node_modules/date-fns/_lib/format/longFormatters.js
var dateLongFormatter = (pattern, formatLong2) => {
  switch (pattern) {
    case "P":
      return formatLong2.date({ width: "short" });
    case "PP":
      return formatLong2.date({ width: "medium" });
    case "PPP":
      return formatLong2.date({ width: "long" });
    case "PPPP":
    default:
      return formatLong2.date({ width: "full" });
  }
};
var timeLongFormatter = (pattern, formatLong2) => {
  switch (pattern) {
    case "p":
      return formatLong2.time({ width: "short" });
    case "pp":
      return formatLong2.time({ width: "medium" });
    case "ppp":
      return formatLong2.time({ width: "long" });
    case "pppp":
    default:
      return formatLong2.time({ width: "full" });
  }
};
var dateTimeLongFormatter = (pattern, formatLong2) => {
  const matchResult = pattern.match(/(P+)(p+)?/) || [];
  const datePattern = matchResult[1];
  const timePattern = matchResult[2];
  if (!timePattern) {
    return dateLongFormatter(pattern, formatLong2);
  }
  let dateTimeFormat;
  switch (datePattern) {
    case "P":
      dateTimeFormat = formatLong2.dateTime({ width: "short" });
      break;
    case "PP":
      dateTimeFormat = formatLong2.dateTime({ width: "medium" });
      break;
    case "PPP":
      dateTimeFormat = formatLong2.dateTime({ width: "long" });
      break;
    case "PPPP":
    default:
      dateTimeFormat = formatLong2.dateTime({ width: "full" });
      break;
  }
  return dateTimeFormat.replace("{{date}}", dateLongFormatter(datePattern, formatLong2)).replace("{{time}}", timeLongFormatter(timePattern, formatLong2));
};
var longFormatters = {
  p: timeLongFormatter,
  P: dateTimeLongFormatter
};

// node_modules/date-fns/_lib/protectedTokens.js
var dayOfYearTokenRE = /^D+$/;
var weekYearTokenRE = /^Y+$/;
var throwTokens = ["D", "DD", "YY", "YYYY"];
function isProtectedDayOfYearToken(token) {
  return dayOfYearTokenRE.test(token);
}
function isProtectedWeekYearToken(token) {
  return weekYearTokenRE.test(token);
}
function warnOrThrowProtectedError(token, format6, input) {
  const _message = message(token, format6, input);
  console.warn(_message);
  if (throwTokens.includes(token)) throw new RangeError(_message);
}
function message(token, format6, input) {
  const subject = token[0] === "Y" ? "years" : "days of the month";
  return `Use \`${token.toLowerCase()}\` instead of \`${token}\` (in \`${format6}\`) for formatting ${subject} to the input \`${input}\`; see: https://github.com/date-fns/date-fns/blob/master/docs/unicodeTokens.md`;
}

// node_modules/date-fns/format.js
var formattingTokensRegExp = /[yYQqMLwIdDecihHKkms]o|(\w)\1*|''|'(''|[^'])+('|$)|./g;
var longFormattingTokensRegExp = /P+p+|P+|p+|''|'(''|[^'])+('|$)|./g;
var escapedStringRegExp = /^'([^]*?)'?$/;
var doubleQuoteRegExp = /''/g;
var unescapedLatinCharacterRegExp = /[a-zA-Z]/;
function format(date, formatStr, options) {
  const defaultOptions2 = getDefaultOptions();
  const locale = options?.locale ?? defaultOptions2.locale ?? enUS;
  const firstWeekContainsDate = options?.firstWeekContainsDate ?? options?.locale?.options?.firstWeekContainsDate ?? defaultOptions2.firstWeekContainsDate ?? defaultOptions2.locale?.options?.firstWeekContainsDate ?? 1;
  const weekStartsOn = options?.weekStartsOn ?? options?.locale?.options?.weekStartsOn ?? defaultOptions2.weekStartsOn ?? defaultOptions2.locale?.options?.weekStartsOn ?? 0;
  const originalDate = toDate(date, options?.in);
  if (!isValid(originalDate)) {
    throw new RangeError("Invalid time value");
  }
  let parts = formatStr.match(longFormattingTokensRegExp).map((substring) => {
    const firstCharacter = substring[0];
    if (firstCharacter === "p" || firstCharacter === "P") {
      const longFormatter = longFormatters[firstCharacter];
      return longFormatter(substring, locale.formatLong);
    }
    return substring;
  }).join("").match(formattingTokensRegExp).map((substring) => {
    if (substring === "''") {
      return { isToken: false, value: "'" };
    }
    const firstCharacter = substring[0];
    if (firstCharacter === "'") {
      return { isToken: false, value: cleanEscapedString(substring) };
    }
    if (formatters[firstCharacter]) {
      return { isToken: true, value: substring };
    }
    if (firstCharacter.match(unescapedLatinCharacterRegExp)) {
      throw new RangeError(
        "Format string contains an unescaped latin alphabet character `" + firstCharacter + "`"
      );
    }
    return { isToken: false, value: substring };
  });
  if (locale.localize.preprocessor) {
    parts = locale.localize.preprocessor(originalDate, parts);
  }
  const formatterOptions = {
    firstWeekContainsDate,
    weekStartsOn,
    locale
  };
  return parts.map((part) => {
    if (!part.isToken) return part.value;
    const token = part.value;
    if (!options?.useAdditionalWeekYearTokens && isProtectedWeekYearToken(token) || !options?.useAdditionalDayOfYearTokens && isProtectedDayOfYearToken(token)) {
      warnOrThrowProtectedError(token, formatStr, String(date));
    }
    const formatter = formatters[token[0]];
    return formatter(originalDate, token, locale.localize, formatterOptions);
  }).join("");
}
function cleanEscapedString(input) {
  const matched = input.match(escapedStringRegExp);
  if (!matched) {
    return input;
  }
  return matched[1].replace(doubleQuoteRegExp, "'");
}

// node_modules/date-fns/subDays.js
function subDays(date, amount, options) {
  return addDays(date, -amount, options);
}

// node_modules/date-fns/subMonths.js
function subMonths(date, amount, options) {
  return addMonths(date, -amount, options);
}

// node_modules/date-fns/subWeeks.js
function subWeeks(date, amount, options) {
  return addWeeks(date, -amount, options);
}

// node_modules/date-fns/subYears.js
function subYears(date, amount, options) {
  return addYears(date, -amount, options);
}

// node_modules/@wordpress/dataviews/build-module/utils/operators.mjs
var import_i18n2 = __toESM(require_i18n(), 1);
var import_element4 = __toESM(require_element(), 1);
var import_date = __toESM(require_date(), 1);
var import_jsx_runtime11 = __toESM(require_jsx_runtime(), 1);
var filterTextWrappers = {
  Name: /* @__PURE__ */ (0, import_jsx_runtime11.jsx)("span", { className: "dataviews-filters__summary-filter-text-name" }),
  Value: /* @__PURE__ */ (0, import_jsx_runtime11.jsx)("span", { className: "dataviews-filters__summary-filter-text-value" })
};
function getRelativeDate(value, unit) {
  switch (unit) {
    case "days":
      return subDays(/* @__PURE__ */ new Date(), value);
    case "weeks":
      return subWeeks(/* @__PURE__ */ new Date(), value);
    case "months":
      return subMonths(/* @__PURE__ */ new Date(), value);
    case "years":
      return subYears(/* @__PURE__ */ new Date(), value);
    default:
      return /* @__PURE__ */ new Date();
  }
}
var isNoneOperatorDefinition = {
  /* translators: DataViews operator name */
  label: (0, import_i18n2.__)("Is none of"),
  filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
    (0, import_i18n2.sprintf)(
      /* translators: 1: Filter name (e.g. "Author"). 2: Filter value (e.g. "Admin"): "Author is none of: Admin, Editor". */
      (0, import_i18n2.__)("<Name>%1$s is none of: </Name><Value>%2$s</Value>"),
      filter.name,
      activeElements.map((element) => element.label).join(", ")
    ),
    filterTextWrappers
  ),
  filter: (item, field, filterValue) => {
    if (!filterValue?.length) {
      return true;
    }
    const fieldValue = field.getValue({ item });
    if (Array.isArray(fieldValue)) {
      return !filterValue.some(
        (fv) => fieldValue.includes(fv)
      );
    } else if (typeof fieldValue === "string") {
      return !filterValue.includes(fieldValue);
    }
    return false;
  },
  selection: "multi"
};
var OPERATORS = [
  {
    name: OPERATOR_IS_ANY,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Includes"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Author"). 2: Filter value (e.g. "Admin"): "Author is any: Admin, Editor". */
        (0, import_i18n2.__)("<Name>%1$s includes: </Name><Value>%2$s</Value>"),
        filter.name,
        activeElements.map((element) => element.label).join(", ")
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (!filterValue?.length) {
        return true;
      }
      const fieldValue = field.getValue({ item });
      if (Array.isArray(fieldValue)) {
        return filterValue.some(
          (fv) => fieldValue.includes(fv)
        );
      } else if (typeof fieldValue === "string") {
        return filterValue.includes(fieldValue);
      }
      return false;
    },
    selection: "multi"
  },
  {
    name: OPERATOR_IS_NONE,
    ...isNoneOperatorDefinition
  },
  {
    name: OPERATOR_IS_ALL,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Includes all"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Author"). 2: Filter value (e.g. "Admin"): "Author includes all: Admin, Editor". */
        (0, import_i18n2.__)("<Name>%1$s includes all: </Name><Value>%2$s</Value>"),
        filter.name,
        activeElements.map((element) => element.label).join(", ")
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (!filterValue?.length) {
        return true;
      }
      return filterValue.every((value) => {
        return field.getValue({ item })?.includes(value);
      });
    },
    selection: "multi"
  },
  {
    name: OPERATOR_IS_NOT_ALL,
    ...isNoneOperatorDefinition
  },
  {
    name: OPERATOR_BETWEEN,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Between (inc)"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Item count"). 2: Filter value min. 3: Filter value max. e.g.: "Item count between (inc): 10 and 180". */
        (0, import_i18n2.__)(
          "<Name>%1$s between (inc): </Name><Value>%2$s and %3$s</Value>"
        ),
        filter.name,
        activeElements[0].label[0],
        activeElements[0].label[1]
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (!Array.isArray(filterValue) || filterValue.length !== 2 || filterValue[0] === void 0 || filterValue[1] === void 0) {
        return true;
      }
      const fieldValue = field.getValue({ item });
      if (typeof fieldValue === "number" || fieldValue instanceof Date || typeof fieldValue === "string") {
        return fieldValue >= filterValue[0] && fieldValue <= filterValue[1];
      }
      return false;
    },
    selection: "custom"
  },
  {
    name: OPERATOR_IN_THE_PAST,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("In the past"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Date"). 2: Filter value (e.g. "7 days"): "Date is in the past: 7 days". */
        (0, import_i18n2.__)(
          "<Name>%1$s is in the past: </Name><Value>%2$s</Value>"
        ),
        filter.name,
        `${activeElements[0].value.value} ${activeElements[0].value.unit}`
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue?.value === void 0 || filterValue?.unit === void 0) {
        return true;
      }
      const targetDate = getRelativeDate(
        filterValue.value,
        filterValue.unit
      );
      const fieldValue = (0, import_date.getDate)(field.getValue({ item }));
      return fieldValue >= targetDate && fieldValue <= /* @__PURE__ */ new Date();
    },
    selection: "custom"
  },
  {
    name: OPERATOR_OVER,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Over"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Date"). 2: Filter value (e.g. "7 days"): "Date is over: 7 days". */
        (0, import_i18n2.__)("<Name>%1$s is over: </Name><Value>%2$s</Value>"),
        filter.name,
        `${activeElements[0].value.value} ${activeElements[0].value.unit}`
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue?.value === void 0 || filterValue?.unit === void 0) {
        return true;
      }
      const targetDate = getRelativeDate(
        filterValue.value,
        filterValue.unit
      );
      const fieldValue = (0, import_date.getDate)(field.getValue({ item }));
      return fieldValue < targetDate;
    },
    selection: "custom"
  },
  {
    name: OPERATOR_IS,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Is"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Author"). 2: Filter value (e.g. "Admin"): "Author is: Admin". */
        (0, import_i18n2.__)("<Name>%1$s is: </Name><Value>%2$s</Value>"),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      return filterValue === field.getValue({ item }) || filterValue === void 0;
    },
    selection: "single"
  },
  {
    name: OPERATOR_IS_NOT,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Is not"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Author"). 2: Filter value (e.g. "Admin"): "Author is not: Admin". */
        (0, import_i18n2.__)("<Name>%1$s is not: </Name><Value>%2$s</Value>"),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      return filterValue !== field.getValue({ item });
    },
    selection: "single"
  },
  {
    name: OPERATOR_LESS_THAN,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Less than"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Count"). 2: Filter value (e.g. "10"): "Count is less than: 10". */
        (0, import_i18n2.__)("<Name>%1$s is less than: </Name><Value>%2$s</Value>"),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const fieldValue = field.getValue({ item });
      return fieldValue < filterValue;
    },
    selection: "single"
  },
  {
    name: OPERATOR_GREATER_THAN,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Greater than"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Count"). 2: Filter value (e.g. "10"): "Count is greater than: 10". */
        (0, import_i18n2.__)(
          "<Name>%1$s is greater than: </Name><Value>%2$s</Value>"
        ),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const fieldValue = field.getValue({ item });
      return fieldValue > filterValue;
    },
    selection: "single"
  },
  {
    name: OPERATOR_LESS_THAN_OR_EQUAL,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Less than or equal"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Count"). 2: Filter value (e.g. "10"): "Count is less than or equal to: 10". */
        (0, import_i18n2.__)(
          "<Name>%1$s is less than or equal to: </Name><Value>%2$s</Value>"
        ),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const fieldValue = field.getValue({ item });
      return fieldValue <= filterValue;
    },
    selection: "single"
  },
  {
    name: OPERATOR_GREATER_THAN_OR_EQUAL,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Greater than or equal"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Count"). 2: Filter value (e.g. "10"): "Count is greater than or equal to: 10". */
        (0, import_i18n2.__)(
          "<Name>%1$s is greater than or equal to: </Name><Value>%2$s</Value>"
        ),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const fieldValue = field.getValue({ item });
      return fieldValue >= filterValue;
    },
    selection: "single"
  },
  {
    name: OPERATOR_BEFORE,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Before"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Date"). 2: Filter value (e.g. "2024-01-01"): "Date is before: 2024-01-01". */
        (0, import_i18n2.__)("<Name>%1$s is before: </Name><Value>%2$s</Value>"),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const filterDate = (0, import_date.getDate)(filterValue);
      const fieldDate = (0, import_date.getDate)(field.getValue({ item }));
      return fieldDate < filterDate;
    },
    selection: "single"
  },
  {
    name: OPERATOR_AFTER,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("After"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Date"). 2: Filter value (e.g. "2024-01-01"): "Date is after: 2024-01-01". */
        (0, import_i18n2.__)("<Name>%1$s is after: </Name><Value>%2$s</Value>"),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const filterDate = (0, import_date.getDate)(filterValue);
      const fieldDate = (0, import_date.getDate)(field.getValue({ item }));
      return fieldDate > filterDate;
    },
    selection: "single"
  },
  {
    name: OPERATOR_BEFORE_INC,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Before (inc)"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Date"). 2: Filter value (e.g. "2024-01-01"): "Date is on or before: 2024-01-01". */
        (0, import_i18n2.__)(
          "<Name>%1$s is on or before: </Name><Value>%2$s</Value>"
        ),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const filterDate = (0, import_date.getDate)(filterValue);
      const fieldDate = (0, import_date.getDate)(field.getValue({ item }));
      return fieldDate <= filterDate;
    },
    selection: "single"
  },
  {
    name: OPERATOR_AFTER_INC,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("After (inc)"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Date"). 2: Filter value (e.g. "2024-01-01"): "Date is on or after: 2024-01-01". */
        (0, import_i18n2.__)(
          "<Name>%1$s is on or after: </Name><Value>%2$s</Value>"
        ),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const filterDate = (0, import_date.getDate)(filterValue);
      const fieldDate = (0, import_date.getDate)(field.getValue({ item }));
      return fieldDate >= filterDate;
    },
    selection: "single"
  },
  {
    name: OPERATOR_CONTAINS,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Contains"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Title"). 2: Filter value (e.g. "Hello"): "Title contains: Hello". */
        (0, import_i18n2.__)("<Name>%1$s contains: </Name><Value>%2$s</Value>"),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const fieldValue = field.getValue({ item });
      return typeof fieldValue === "string" && filterValue && fieldValue.toLowerCase().includes(String(filterValue).toLowerCase());
    },
    selection: "single"
  },
  {
    name: OPERATOR_NOT_CONTAINS,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Doesn't contain"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Title"). 2: Filter value (e.g. "Hello"): "Title doesn't contain: Hello". */
        (0, import_i18n2.__)(
          "<Name>%1$s doesn't contain: </Name><Value>%2$s</Value>"
        ),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const fieldValue = field.getValue({ item });
      return typeof fieldValue === "string" && filterValue && !fieldValue.toLowerCase().includes(String(filterValue).toLowerCase());
    },
    selection: "single"
  },
  {
    name: OPERATOR_STARTS_WITH,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Starts with"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Title"). 2: Filter value (e.g. "Hello"): "Title starts with: Hello". */
        (0, import_i18n2.__)("<Name>%1$s starts with: </Name><Value>%2$s</Value>"),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const fieldValue = field.getValue({ item });
      return typeof fieldValue === "string" && filterValue && fieldValue.toLowerCase().startsWith(String(filterValue).toLowerCase());
    },
    selection: "single"
  },
  {
    name: OPERATOR_ON,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("On"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Date"). 2: Filter value (e.g. "2024-01-01"): "Date is: 2024-01-01". */
        (0, import_i18n2.__)("<Name>%1$s is: </Name><Value>%2$s</Value>"),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const filterDate = (0, import_date.getDate)(filterValue);
      const fieldDate = (0, import_date.getDate)(field.getValue({ item }));
      return filterDate.getTime() === fieldDate.getTime();
    },
    selection: "single"
  },
  {
    name: OPERATOR_NOT_ON,
    /* translators: DataViews operator name */
    label: (0, import_i18n2.__)("Not on"),
    filterText: (filter, activeElements) => (0, import_element4.createInterpolateElement)(
      (0, import_i18n2.sprintf)(
        /* translators: 1: Filter name (e.g. "Date"). 2: Filter value (e.g. "2024-01-01"): "Date is not: 2024-01-01". */
        (0, import_i18n2.__)("<Name>%1$s is not: </Name><Value>%2$s</Value>"),
        filter.name,
        activeElements[0].label
      ),
      filterTextWrappers
    ),
    filter(item, field, filterValue) {
      if (filterValue === void 0) {
        return true;
      }
      const filterDate = (0, import_date.getDate)(filterValue);
      const fieldDate = (0, import_date.getDate)(field.getValue({ item }));
      return filterDate.getTime() !== fieldDate.getTime();
    },
    selection: "single"
  }
];
var getOperatorByName = (name) => OPERATORS.find((op) => op.name === name);
var getAllOperatorNames = () => OPERATORS.map((op) => op.name);

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/checkbox.mjs
var import_components = __toESM(require_components(), 1);
var import_element5 = __toESM(require_element(), 1);

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/utils/get-custom-validity.mjs
function getCustomValidity(isValid2, validity) {
  let customValidity;
  if (isValid2?.required && validity?.required) {
    customValidity = validity?.required?.message ? validity.required : void 0;
  } else if (isValid2?.pattern && validity?.pattern) {
    customValidity = validity.pattern;
  } else if (isValid2?.min && validity?.min) {
    customValidity = validity.min;
  } else if (isValid2?.max && validity?.max) {
    customValidity = validity.max;
  } else if (isValid2?.minLength && validity?.minLength) {
    customValidity = validity.minLength;
  } else if (isValid2?.maxLength && validity?.maxLength) {
    customValidity = validity.maxLength;
  } else if (isValid2?.elements && validity?.elements) {
    customValidity = validity.elements;
  } else if (validity?.custom) {
    customValidity = validity.custom;
  }
  return customValidity;
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/checkbox.mjs
var import_jsx_runtime12 = __toESM(require_jsx_runtime(), 1);
var { ValidatedCheckboxControl } = unlock(import_components.privateApis);
function Checkbox({
  field,
  onChange,
  data,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const { getValue, setValue, label, description, isValid: isValid2 } = field;
  const onChangeControl = (0, import_element5.useCallback)(() => {
    onChange(
      setValue({ item: data, value: !getValue({ item: data }) })
    );
  }, [data, getValue, onChange, setValue]);
  return /* @__PURE__ */ (0, import_jsx_runtime12.jsx)(
    ValidatedCheckboxControl,
    {
      required: !!field.isValid?.required,
      markWhenOptional,
      customValidity: getCustomValidity(isValid2, validity),
      hidden: hideLabelFromVision,
      label,
      help: description,
      checked: getValue({ item: data }),
      onChange: onChangeControl
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/combobox.mjs
var import_components2 = __toESM(require_components(), 1);
var import_element6 = __toESM(require_element(), 1);
var import_jsx_runtime13 = __toESM(require_jsx_runtime(), 1);
var { ValidatedComboboxControl } = unlock(import_components2.privateApis);
function Combobox({
  data,
  field,
  onChange,
  hideLabelFromVision,
  validity
}) {
  const { label, description, placeholder, getValue, setValue, isValid: isValid2 } = field;
  const value = getValue({ item: data }) ?? "";
  const onChangeControl = (0, import_element6.useCallback)(
    (newValue) => onChange(setValue({ item: data, value: newValue ?? "" })),
    [data, onChange, setValue]
  );
  const { elements, isLoading } = useElements({
    elements: field.elements,
    getElements: field.getElements
  });
  if (isLoading) {
    return /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(import_components2.Spinner, {});
  }
  return /* @__PURE__ */ (0, import_jsx_runtime13.jsx)(
    ValidatedComboboxControl,
    {
      required: !!field.isValid?.required,
      customValidity: getCustomValidity(isValid2, validity),
      label,
      value,
      help: description,
      placeholder,
      options: elements,
      onChange: onChangeControl,
      hideLabelFromVision,
      allowReset: true,
      expandOnFocus: true
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/datetime.mjs
var import_components4 = __toESM(require_components(), 1);
var import_element8 = __toESM(require_element(), 1);
var import_i18n4 = __toESM(require_i18n(), 1);
var import_date3 = __toESM(require_date(), 1);

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/utils/relative-date-control.mjs
var import_components3 = __toESM(require_components(), 1);
var import_element7 = __toESM(require_element(), 1);
var import_i18n3 = __toESM(require_i18n(), 1);
var import_jsx_runtime14 = __toESM(require_jsx_runtime(), 1);
var TIME_UNITS_OPTIONS = {
  [OPERATOR_IN_THE_PAST]: [
    { value: "days", label: (0, import_i18n3.__)("Days") },
    { value: "weeks", label: (0, import_i18n3.__)("Weeks") },
    { value: "months", label: (0, import_i18n3.__)("Months") },
    { value: "years", label: (0, import_i18n3.__)("Years") }
  ],
  [OPERATOR_OVER]: [
    { value: "days", label: (0, import_i18n3.__)("Days ago") },
    { value: "weeks", label: (0, import_i18n3.__)("Weeks ago") },
    { value: "months", label: (0, import_i18n3.__)("Months ago") },
    { value: "years", label: (0, import_i18n3.__)("Years ago") }
  ]
};
function RelativeDateControl({
  className,
  data,
  field,
  onChange,
  hideLabelFromVision,
  operator
}) {
  const options = TIME_UNITS_OPTIONS[operator === OPERATOR_IN_THE_PAST ? "inThePast" : "over"];
  const { id, label, getValue, setValue } = field;
  const fieldValue = getValue({ item: data });
  const { value: relValue = "", unit = options[0].value } = fieldValue && typeof fieldValue === "object" ? fieldValue : {};
  const onChangeValue = (0, import_element7.useCallback)(
    (newValue) => onChange(
      setValue({
        item: data,
        value: { value: Number(newValue), unit }
      })
    ),
    [onChange, setValue, data, unit]
  );
  const onChangeUnit = (0, import_element7.useCallback)(
    (newUnit) => onChange(
      setValue({
        item: data,
        value: { value: relValue, unit: newUnit }
      })
    ),
    [onChange, setValue, data, relValue]
  );
  return /* @__PURE__ */ (0, import_jsx_runtime14.jsx)(
    import_components3.BaseControl,
    {
      id,
      className: clsx_default(className, "dataviews-controls__relative-date"),
      label,
      hideLabelFromVision,
      children: /* @__PURE__ */ (0, import_jsx_runtime14.jsxs)(Stack, { direction: "row", gap: "sm", children: [
        /* @__PURE__ */ (0, import_jsx_runtime14.jsx)(
          import_components3.__experimentalNumberControl,
          {
            __next40pxDefaultSize: true,
            className: "dataviews-controls__relative-date-number",
            spinControls: "none",
            min: 1,
            step: 1,
            value: relValue,
            onChange: onChangeValue
          }
        ),
        /* @__PURE__ */ (0, import_jsx_runtime14.jsx)(
          import_components3.SelectControl,
          {
            className: "dataviews-controls__relative-date-unit",
            __next40pxDefaultSize: true,
            label: (0, import_i18n3.__)("Unit"),
            value: unit,
            options,
            onChange: onChangeUnit,
            hideLabelFromVision: true
          }
        )
      ] })
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/parse-date-time.mjs
var import_date2 = __toESM(require_date(), 1);
function parseDateTime(dateTimeString) {
  if (!dateTimeString) {
    return null;
  }
  const parsed = (0, import_date2.getDate)(dateTimeString);
  return parsed && isValid(parsed) ? parsed : null;
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/datetime.mjs
var import_jsx_runtime15 = __toESM(require_jsx_runtime(), 1);
var { DateCalendar, ValidatedInputControl } = unlock(import_components4.privateApis);
var formatDateTime = (value) => {
  if (!value) {
    return "";
  }
  return (0, import_date3.dateI18n)("Y-m-d\\TH:i", (0, import_date3.getDate)(value));
};
function CalendarDateTimeControl({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const { id, label, description, setValue, getValue, isValid: isValid2 } = field;
  const fieldValue = getValue({ item: data });
  const value = typeof fieldValue === "string" ? fieldValue : void 0;
  const [calendarMonth, setCalendarMonth] = (0, import_element8.useState)(() => {
    const parsedDate = parseDateTime(value);
    return parsedDate || /* @__PURE__ */ new Date();
  });
  const inputControlRef = (0, import_element8.useRef)(null);
  const validationTimeoutRef = (0, import_element8.useRef)(void 0);
  const previousFocusRef = (0, import_element8.useRef)(null);
  const onChangeCallback = (0, import_element8.useCallback)(
    (newValue) => onChange(setValue({ item: data, value: newValue })),
    [data, onChange, setValue]
  );
  (0, import_element8.useEffect)(() => {
    return () => {
      if (validationTimeoutRef.current) {
        clearTimeout(validationTimeoutRef.current);
      }
    };
  }, []);
  const onSelectDate = (0, import_element8.useCallback)(
    (newDate) => {
      let dateTimeValue;
      if (newDate) {
        const wpDate = (0, import_date3.dateI18n)("Y-m-d", newDate);
        let wpTime;
        if (value) {
          wpTime = (0, import_date3.dateI18n)("H:i", (0, import_date3.getDate)(value));
        } else {
          wpTime = (0, import_date3.dateI18n)("H:i", newDate);
        }
        const finalDateTime = (0, import_date3.getDate)(`${wpDate}T${wpTime}`);
        dateTimeValue = finalDateTime.toISOString();
        onChangeCallback(dateTimeValue);
        if (validationTimeoutRef.current) {
          clearTimeout(validationTimeoutRef.current);
        }
      } else {
        onChangeCallback(void 0);
      }
      previousFocusRef.current = inputControlRef.current && inputControlRef.current.ownerDocument.activeElement;
      validationTimeoutRef.current = setTimeout(() => {
        if (inputControlRef.current) {
          inputControlRef.current.focus();
          inputControlRef.current.blur();
          onChangeCallback(dateTimeValue);
          if (previousFocusRef.current && previousFocusRef.current instanceof HTMLElement) {
            previousFocusRef.current.focus();
          }
        }
      }, 0);
    },
    [onChangeCallback, value]
  );
  const handleManualDateTimeChange = (0, import_element8.useCallback)(
    (newValue) => {
      if (newValue) {
        const dateTime = (0, import_date3.getDate)(newValue);
        onChangeCallback(dateTime.toISOString());
        const parsedDate = parseDateTime(dateTime.toISOString());
        if (parsedDate) {
          setCalendarMonth(parsedDate);
        }
      } else {
        onChangeCallback(void 0);
      }
    },
    [onChangeCallback]
  );
  const { format: fieldFormat } = field;
  const weekStartsOn = fieldFormat.weekStartsOn ?? (0, import_date3.getSettings)().l10n.startOfWeek;
  const {
    timezone: { string: timezoneString }
  } = (0, import_date3.getSettings)();
  let displayLabel = label;
  if (isValid2?.required && !markWhenOptional && !hideLabelFromVision) {
    displayLabel = `${label} (${(0, import_i18n4.__)("Required")})`;
  } else if (!isValid2?.required && markWhenOptional && !hideLabelFromVision) {
    displayLabel = `${label} (${(0, import_i18n4.__)("Optional")})`;
  }
  return /* @__PURE__ */ (0, import_jsx_runtime15.jsx)(
    import_components4.BaseControl,
    {
      id,
      label: displayLabel,
      help: description,
      hideLabelFromVision,
      children: /* @__PURE__ */ (0, import_jsx_runtime15.jsxs)(Stack, { direction: "column", gap: "lg", children: [
        /* @__PURE__ */ (0, import_jsx_runtime15.jsx)(
          DateCalendar,
          {
            style: { width: "100%" },
            selected: value ? parseDateTime(value) || void 0 : void 0,
            onSelect: onSelectDate,
            month: calendarMonth,
            onMonthChange: setCalendarMonth,
            timeZone: timezoneString || void 0,
            weekStartsOn
          }
        ),
        /* @__PURE__ */ (0, import_jsx_runtime15.jsx)(
          ValidatedInputControl,
          {
            ref: inputControlRef,
            __next40pxDefaultSize: true,
            required: !!isValid2?.required,
            customValidity: getCustomValidity(isValid2, validity),
            type: "datetime-local",
            label: (0, import_i18n4.__)("Date time"),
            hideLabelFromVision: true,
            value: formatDateTime(value),
            onChange: handleManualDateTimeChange
          }
        )
      ] })
    }
  );
}
function DateTime({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  operator,
  validity
}) {
  if (operator === OPERATOR_IN_THE_PAST || operator === OPERATOR_OVER) {
    return /* @__PURE__ */ (0, import_jsx_runtime15.jsx)(
      RelativeDateControl,
      {
        className: "dataviews-controls__datetime",
        data,
        field,
        onChange,
        hideLabelFromVision,
        operator
      }
    );
  }
  return /* @__PURE__ */ (0, import_jsx_runtime15.jsx)(
    CalendarDateTimeControl,
    {
      data,
      field,
      onChange,
      hideLabelFromVision,
      markWhenOptional,
      validity
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/date.mjs
var import_components5 = __toESM(require_components(), 1);
var import_element9 = __toESM(require_element(), 1);
var import_i18n5 = __toESM(require_i18n(), 1);
var import_date4 = __toESM(require_date(), 1);
var import_jsx_runtime16 = __toESM(require_jsx_runtime(), 1);
var { DateCalendar: DateCalendar2, DateRangeCalendar } = unlock(import_components5.privateApis);
var DATE_PRESETS = [
  {
    id: "today",
    label: (0, import_i18n5.__)("Today"),
    getValue: () => (0, import_date4.getDate)(null)
  },
  {
    id: "yesterday",
    label: (0, import_i18n5.__)("Yesterday"),
    getValue: () => {
      const today = (0, import_date4.getDate)(null);
      return subDays(today, 1);
    }
  },
  {
    id: "past-week",
    label: (0, import_i18n5.__)("Past week"),
    getValue: () => {
      const today = (0, import_date4.getDate)(null);
      return subDays(today, 7);
    }
  },
  {
    id: "past-month",
    label: (0, import_i18n5.__)("Past month"),
    getValue: () => {
      const today = (0, import_date4.getDate)(null);
      return subMonths(today, 1);
    }
  }
];
var DATE_RANGE_PRESETS = [
  {
    id: "last-7-days",
    label: (0, import_i18n5.__)("Last 7 days"),
    getValue: () => {
      const today = (0, import_date4.getDate)(null);
      return [subDays(today, 7), today];
    }
  },
  {
    id: "last-30-days",
    label: (0, import_i18n5.__)("Last 30 days"),
    getValue: () => {
      const today = (0, import_date4.getDate)(null);
      return [subDays(today, 30), today];
    }
  },
  {
    id: "month-to-date",
    label: (0, import_i18n5.__)("Month to date"),
    getValue: () => {
      const today = (0, import_date4.getDate)(null);
      return [startOfMonth(today), today];
    }
  },
  {
    id: "last-year",
    label: (0, import_i18n5.__)("Last year"),
    getValue: () => {
      const today = (0, import_date4.getDate)(null);
      return [subYears(today, 1), today];
    }
  },
  {
    id: "year-to-date",
    label: (0, import_i18n5.__)("Year to date"),
    getValue: () => {
      const today = (0, import_date4.getDate)(null);
      return [startOfYear(today), today];
    }
  }
];
var parseDate = (dateString) => {
  if (!dateString) {
    return null;
  }
  const parsed = (0, import_date4.getDate)(dateString);
  return parsed && isValid(parsed) ? parsed : null;
};
var formatDate = (date) => {
  if (!date) {
    return "";
  }
  return typeof date === "string" ? date : format(date, "yyyy-MM-dd");
};
function ValidatedDateControl({
  field,
  validity,
  inputRefs,
  isTouched,
  setIsTouched,
  children
}) {
  const { isValid: isValid2 } = field;
  const [customValidity, setCustomValidity] = (0, import_element9.useState)(void 0);
  const validateRefs = (0, import_element9.useCallback)(() => {
    const refs = Array.isArray(inputRefs) ? inputRefs : [inputRefs];
    for (const ref of refs) {
      const input = ref.current;
      if (input && !input.validity.valid) {
        setCustomValidity({
          type: "invalid",
          message: input.validationMessage
        });
        return;
      }
    }
    setCustomValidity(void 0);
  }, [inputRefs]);
  (0, import_element9.useEffect)(() => {
    const refs = Array.isArray(inputRefs) ? inputRefs : [inputRefs];
    const result = validity ? getCustomValidity(isValid2, validity) : void 0;
    for (const ref of refs) {
      const input = ref.current;
      if (input) {
        input.setCustomValidity(
          result?.type === "invalid" && result.message ? result.message : ""
        );
      }
    }
  }, [inputRefs, isValid2, validity]);
  (0, import_element9.useEffect)(() => {
    const refs = Array.isArray(inputRefs) ? inputRefs : [inputRefs];
    const handleInvalid = (event) => {
      event.preventDefault();
      setIsTouched(true);
    };
    for (const ref of refs) {
      ref.current?.addEventListener("invalid", handleInvalid);
    }
    return () => {
      for (const ref of refs) {
        ref.current?.removeEventListener("invalid", handleInvalid);
      }
    };
  }, [inputRefs, setIsTouched]);
  (0, import_element9.useEffect)(() => {
    if (!isTouched) {
      return;
    }
    const result = validity ? getCustomValidity(isValid2, validity) : void 0;
    if (result) {
      setCustomValidity(result);
    } else {
      validateRefs();
    }
  }, [isTouched, isValid2, validity, validateRefs]);
  const onBlur = (event) => {
    if (isTouched) {
      return;
    }
    if (!event.relatedTarget || !event.currentTarget.contains(event.relatedTarget)) {
      setIsTouched(true);
    }
  };
  return /* @__PURE__ */ (0, import_jsx_runtime16.jsxs)("div", { onBlur, children: [
    children,
    /* @__PURE__ */ (0, import_jsx_runtime16.jsx)("div", { "aria-live": "polite", children: customValidity && /* @__PURE__ */ (0, import_jsx_runtime16.jsxs)(
      "p",
      {
        className: clsx_default(
          "components-validated-control__indicator",
          customValidity.type === "invalid" ? "is-invalid" : void 0
        ),
        children: [
          /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
            import_components5.Icon,
            {
              className: "components-validated-control__indicator-icon",
              icon: error_default,
              size: 16,
              fill: "currentColor"
            }
          ),
          customValidity.message
        ]
      }
    ) })
  ] });
}
function CalendarDateControl({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const {
    id,
    label,
    setValue,
    getValue,
    isValid: isValid2,
    format: fieldFormat
  } = field;
  const [selectedPresetId, setSelectedPresetId] = (0, import_element9.useState)(
    null
  );
  const weekStartsOn = fieldFormat.weekStartsOn ?? (0, import_date4.getSettings)().l10n.startOfWeek;
  const fieldValue = getValue({ item: data });
  const value = typeof fieldValue === "string" ? fieldValue : void 0;
  const [calendarMonth, setCalendarMonth] = (0, import_element9.useState)(() => {
    const parsedDate = parseDate(value);
    return parsedDate || /* @__PURE__ */ new Date();
  });
  const [isTouched, setIsTouched] = (0, import_element9.useState)(false);
  const validityTargetRef = (0, import_element9.useRef)(null);
  const onChangeCallback = (0, import_element9.useCallback)(
    (newValue) => onChange(setValue({ item: data, value: newValue })),
    [data, onChange, setValue]
  );
  const onSelectDate = (0, import_element9.useCallback)(
    (newDate) => {
      const dateValue = newDate ? format(newDate, "yyyy-MM-dd") : void 0;
      onChangeCallback(dateValue);
      setSelectedPresetId(null);
      setIsTouched(true);
    },
    [onChangeCallback]
  );
  const handlePresetClick = (0, import_element9.useCallback)(
    (preset) => {
      const presetDate = preset.getValue();
      const dateValue = formatDate(presetDate);
      setCalendarMonth(presetDate);
      onChangeCallback(dateValue);
      setSelectedPresetId(preset.id);
      setIsTouched(true);
    },
    [onChangeCallback]
  );
  const handleManualDateChange = (0, import_element9.useCallback)(
    (newValue) => {
      onChangeCallback(newValue);
      if (newValue) {
        const parsedDate = parseDate(newValue);
        if (parsedDate) {
          setCalendarMonth(parsedDate);
        }
      }
      setSelectedPresetId(null);
      setIsTouched(true);
    },
    [onChangeCallback]
  );
  const {
    timezone: { string: timezoneString }
  } = (0, import_date4.getSettings)();
  let displayLabel = label;
  if (isValid2?.required && !markWhenOptional) {
    displayLabel = `${label} (${(0, import_i18n5.__)("Required")})`;
  } else if (!isValid2?.required && markWhenOptional) {
    displayLabel = `${label} (${(0, import_i18n5.__)("Optional")})`;
  }
  return /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
    ValidatedDateControl,
    {
      field,
      validity,
      inputRefs: validityTargetRef,
      isTouched,
      setIsTouched,
      children: /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
        import_components5.BaseControl,
        {
          id,
          className: "dataviews-controls__date",
          label: displayLabel,
          hideLabelFromVision,
          children: /* @__PURE__ */ (0, import_jsx_runtime16.jsxs)(Stack, { direction: "column", gap: "lg", children: [
            /* @__PURE__ */ (0, import_jsx_runtime16.jsxs)(
              Stack,
              {
                direction: "row",
                gap: "sm",
                wrap: "wrap",
                justify: "flex-start",
                children: [
                  DATE_PRESETS.map((preset) => {
                    const isSelected = selectedPresetId === preset.id;
                    return /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
                      import_components5.Button,
                      {
                        className: "dataviews-controls__date-preset",
                        variant: "tertiary",
                        isPressed: isSelected,
                        size: "small",
                        onClick: () => handlePresetClick(preset),
                        children: preset.label
                      },
                      preset.id
                    );
                  }),
                  /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
                    import_components5.Button,
                    {
                      className: "dataviews-controls__date-preset",
                      variant: "tertiary",
                      isPressed: !selectedPresetId,
                      size: "small",
                      disabled: !!selectedPresetId,
                      accessibleWhenDisabled: false,
                      children: (0, import_i18n5.__)("Custom")
                    }
                  )
                ]
              }
            ),
            /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
              import_components5.__experimentalInputControl,
              {
                __next40pxDefaultSize: true,
                ref: validityTargetRef,
                type: "date",
                label: (0, import_i18n5.__)("Date"),
                hideLabelFromVision: true,
                value,
                onChange: handleManualDateChange,
                required: !!field.isValid?.required
              }
            ),
            /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
              DateCalendar2,
              {
                style: { width: "100%" },
                selected: value ? parseDate(value) || void 0 : void 0,
                onSelect: onSelectDate,
                month: calendarMonth,
                onMonthChange: setCalendarMonth,
                timeZone: timezoneString || void 0,
                weekStartsOn
              }
            )
          ] })
        }
      )
    }
  );
}
function CalendarDateRangeControl({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const { id, label, getValue, setValue, format: fieldFormat } = field;
  let value;
  const fieldValue = getValue({ item: data });
  if (Array.isArray(fieldValue) && fieldValue.length === 2 && fieldValue.every((date) => typeof date === "string")) {
    value = fieldValue;
  }
  const weekStartsOn = fieldFormat.weekStartsOn ?? (0, import_date4.getSettings)().l10n.startOfWeek;
  const onChangeCallback = (0, import_element9.useCallback)(
    (newValue) => {
      onChange(
        setValue({
          item: data,
          value: newValue
        })
      );
    },
    [data, onChange, setValue]
  );
  const [selectedPresetId, setSelectedPresetId] = (0, import_element9.useState)(
    null
  );
  const selectedRange = (0, import_element9.useMemo)(() => {
    if (!value) {
      return { from: void 0, to: void 0 };
    }
    const [from, to] = value;
    return {
      from: parseDate(from) || void 0,
      to: parseDate(to) || void 0
    };
  }, [value]);
  const [calendarMonth, setCalendarMonth] = (0, import_element9.useState)(() => {
    return selectedRange.from || /* @__PURE__ */ new Date();
  });
  const [isTouched, setIsTouched] = (0, import_element9.useState)(false);
  const fromInputRef = (0, import_element9.useRef)(null);
  const toInputRef = (0, import_element9.useRef)(null);
  const updateDateRange = (0, import_element9.useCallback)(
    (fromDate, toDate2) => {
      if (fromDate && toDate2) {
        onChangeCallback([
          formatDate(fromDate),
          formatDate(toDate2)
        ]);
      } else if (!fromDate && !toDate2) {
        onChangeCallback(void 0);
      }
    },
    [onChangeCallback]
  );
  const onSelectCalendarRange = (0, import_element9.useCallback)(
    (newRange) => {
      updateDateRange(newRange?.from, newRange?.to);
      setSelectedPresetId(null);
      setIsTouched(true);
    },
    [updateDateRange]
  );
  const handlePresetClick = (0, import_element9.useCallback)(
    (preset) => {
      const [startDate, endDate] = preset.getValue();
      setCalendarMonth(startDate);
      updateDateRange(startDate, endDate);
      setSelectedPresetId(preset.id);
      setIsTouched(true);
    },
    [updateDateRange]
  );
  const handleManualDateChange = (0, import_element9.useCallback)(
    (fromOrTo, newValue) => {
      const [currentFrom, currentTo] = value || [
        void 0,
        void 0
      ];
      const updatedFrom = fromOrTo === "from" ? newValue : currentFrom;
      const updatedTo = fromOrTo === "to" ? newValue : currentTo;
      updateDateRange(updatedFrom, updatedTo);
      if (newValue) {
        const parsedDate = parseDate(newValue);
        if (parsedDate) {
          setCalendarMonth(parsedDate);
        }
      }
      setSelectedPresetId(null);
      setIsTouched(true);
    },
    [value, updateDateRange]
  );
  const { timezone } = (0, import_date4.getSettings)();
  let displayLabel = label;
  if (field.isValid?.required && !markWhenOptional) {
    displayLabel = `${label} (${(0, import_i18n5.__)("Required")})`;
  } else if (!field.isValid?.required && markWhenOptional) {
    displayLabel = `${label} (${(0, import_i18n5.__)("Optional")})`;
  }
  return /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
    ValidatedDateControl,
    {
      field,
      validity,
      inputRefs: [fromInputRef, toInputRef],
      isTouched,
      setIsTouched,
      children: /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
        import_components5.BaseControl,
        {
          id,
          className: "dataviews-controls__date",
          label: displayLabel,
          hideLabelFromVision,
          children: /* @__PURE__ */ (0, import_jsx_runtime16.jsxs)(Stack, { direction: "column", gap: "lg", children: [
            /* @__PURE__ */ (0, import_jsx_runtime16.jsxs)(
              Stack,
              {
                direction: "row",
                gap: "sm",
                wrap: "wrap",
                justify: "flex-start",
                children: [
                  DATE_RANGE_PRESETS.map((preset) => {
                    const isSelected = selectedPresetId === preset.id;
                    return /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
                      import_components5.Button,
                      {
                        className: "dataviews-controls__date-preset",
                        variant: "tertiary",
                        isPressed: isSelected,
                        size: "small",
                        onClick: () => handlePresetClick(preset),
                        children: preset.label
                      },
                      preset.id
                    );
                  }),
                  /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
                    import_components5.Button,
                    {
                      className: "dataviews-controls__date-preset",
                      variant: "tertiary",
                      isPressed: !selectedPresetId,
                      size: "small",
                      accessibleWhenDisabled: false,
                      disabled: !!selectedPresetId,
                      children: (0, import_i18n5.__)("Custom")
                    }
                  )
                ]
              }
            ),
            /* @__PURE__ */ (0, import_jsx_runtime16.jsxs)(
              Stack,
              {
                direction: "row",
                gap: "sm",
                justify: "space-between",
                className: "dataviews-controls__date-range-inputs",
                children: [
                  /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
                    import_components5.__experimentalInputControl,
                    {
                      __next40pxDefaultSize: true,
                      ref: fromInputRef,
                      type: "date",
                      label: (0, import_i18n5.__)("From"),
                      hideLabelFromVision: true,
                      value: value?.[0],
                      onChange: (newValue) => handleManualDateChange("from", newValue),
                      required: !!field.isValid?.required
                    }
                  ),
                  /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
                    import_components5.__experimentalInputControl,
                    {
                      __next40pxDefaultSize: true,
                      ref: toInputRef,
                      type: "date",
                      label: (0, import_i18n5.__)("To"),
                      hideLabelFromVision: true,
                      value: value?.[1],
                      onChange: (newValue) => handleManualDateChange("to", newValue),
                      required: !!field.isValid?.required
                    }
                  )
                ]
              }
            ),
            /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
              DateRangeCalendar,
              {
                style: { width: "100%" },
                selected: selectedRange,
                onSelect: onSelectCalendarRange,
                month: calendarMonth,
                onMonthChange: setCalendarMonth,
                timeZone: timezone.string || void 0,
                weekStartsOn
              }
            )
          ] })
        }
      )
    }
  );
}
function DateControl({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  operator,
  validity
}) {
  if (operator === OPERATOR_IN_THE_PAST || operator === OPERATOR_OVER) {
    return /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
      RelativeDateControl,
      {
        className: "dataviews-controls__date",
        data,
        field,
        onChange,
        hideLabelFromVision,
        operator
      }
    );
  }
  if (operator === OPERATOR_BETWEEN) {
    return /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
      CalendarDateRangeControl,
      {
        data,
        field,
        onChange,
        hideLabelFromVision,
        markWhenOptional,
        validity
      }
    );
  }
  return /* @__PURE__ */ (0, import_jsx_runtime16.jsx)(
    CalendarDateControl,
    {
      data,
      field,
      onChange,
      hideLabelFromVision,
      markWhenOptional,
      validity
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/select.mjs
var import_components6 = __toESM(require_components(), 1);
var import_element10 = __toESM(require_element(), 1);
var import_jsx_runtime17 = __toESM(require_jsx_runtime(), 1);
var { ValidatedSelectControl } = unlock(import_components6.privateApis);
function Select({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const { type, label, description, getValue, setValue, isValid: isValid2 } = field;
  const isMultiple = type === "array";
  const value = getValue({ item: data }) ?? (isMultiple ? [] : "");
  const onChangeControl = (0, import_element10.useCallback)(
    (newValue) => onChange(setValue({ item: data, value: newValue })),
    [data, onChange, setValue]
  );
  const { elements, isLoading } = useElements({
    elements: field.elements,
    getElements: field.getElements
  });
  if (isLoading) {
    return /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(import_components6.Spinner, {});
  }
  return /* @__PURE__ */ (0, import_jsx_runtime17.jsx)(
    ValidatedSelectControl,
    {
      required: !!field.isValid?.required,
      markWhenOptional,
      customValidity: getCustomValidity(isValid2, validity),
      label,
      value,
      help: description,
      options: elements,
      onChange: onChangeControl,
      __next40pxDefaultSize: true,
      hideLabelFromVision,
      multiple: isMultiple
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/adaptive-select.mjs
var import_jsx_runtime18 = __toESM(require_jsx_runtime(), 1);
var ELEMENTS_THRESHOLD = 10;
function AdaptiveSelect(props) {
  const { field } = props;
  const { elements } = useElements({
    elements: field.elements,
    getElements: field.getElements
  });
  if (elements.length >= ELEMENTS_THRESHOLD) {
    return /* @__PURE__ */ (0, import_jsx_runtime18.jsx)(Combobox, { ...props });
  }
  return /* @__PURE__ */ (0, import_jsx_runtime18.jsx)(Select, { ...props });
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/email.mjs
var import_components8 = __toESM(require_components(), 1);

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/utils/validated-input.mjs
var import_components7 = __toESM(require_components(), 1);
var import_element11 = __toESM(require_element(), 1);
var import_jsx_runtime19 = __toESM(require_jsx_runtime(), 1);
var { ValidatedInputControl: ValidatedInputControl2 } = unlock(import_components7.privateApis);
function ValidatedText({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  type,
  prefix,
  suffix,
  validity
}) {
  const { label, placeholder, description, getValue, setValue, isValid: isValid2 } = field;
  const value = getValue({ item: data });
  const onChangeControl = (0, import_element11.useCallback)(
    (newValue) => onChange(
      setValue({
        item: data,
        value: newValue
      })
    ),
    [data, setValue, onChange]
  );
  return /* @__PURE__ */ (0, import_jsx_runtime19.jsx)(
    ValidatedInputControl2,
    {
      required: !!isValid2.required,
      markWhenOptional,
      customValidity: getCustomValidity(isValid2, validity),
      label,
      placeholder,
      value: value ?? "",
      help: description,
      onChange: onChangeControl,
      hideLabelFromVision,
      type,
      prefix,
      suffix,
      pattern: isValid2.pattern ? isValid2.pattern.constraint : void 0,
      minLength: isValid2.minLength ? isValid2.minLength.constraint : void 0,
      maxLength: isValid2.maxLength ? isValid2.maxLength.constraint : void 0,
      __next40pxDefaultSize: true
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/email.mjs
var import_jsx_runtime20 = __toESM(require_jsx_runtime(), 1);
function Email({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  return /* @__PURE__ */ (0, import_jsx_runtime20.jsx)(
    ValidatedText,
    {
      ...{
        data,
        field,
        onChange,
        hideLabelFromVision,
        markWhenOptional,
        validity,
        type: "email",
        prefix: /* @__PURE__ */ (0, import_jsx_runtime20.jsx)(import_components8.__experimentalInputControlPrefixWrapper, { variant: "icon", children: /* @__PURE__ */ (0, import_jsx_runtime20.jsx)(import_components8.Icon, { icon: envelope_default }) })
      }
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/telephone.mjs
var import_components9 = __toESM(require_components(), 1);
var import_jsx_runtime21 = __toESM(require_jsx_runtime(), 1);
function Telephone({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  return /* @__PURE__ */ (0, import_jsx_runtime21.jsx)(
    ValidatedText,
    {
      ...{
        data,
        field,
        onChange,
        hideLabelFromVision,
        markWhenOptional,
        validity,
        type: "tel",
        prefix: /* @__PURE__ */ (0, import_jsx_runtime21.jsx)(import_components9.__experimentalInputControlPrefixWrapper, { variant: "icon", children: /* @__PURE__ */ (0, import_jsx_runtime21.jsx)(import_components9.Icon, { icon: mobile_default }) })
      }
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/url.mjs
var import_components10 = __toESM(require_components(), 1);
var import_jsx_runtime22 = __toESM(require_jsx_runtime(), 1);
function Url({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  return /* @__PURE__ */ (0, import_jsx_runtime22.jsx)(
    ValidatedText,
    {
      ...{
        data,
        field,
        onChange,
        hideLabelFromVision,
        markWhenOptional,
        validity,
        type: "url",
        prefix: /* @__PURE__ */ (0, import_jsx_runtime22.jsx)(import_components10.__experimentalInputControlPrefixWrapper, { variant: "icon", children: /* @__PURE__ */ (0, import_jsx_runtime22.jsx)(import_components10.Icon, { icon: link_default }) })
      }
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/utils/validated-number.mjs
var import_components11 = __toESM(require_components(), 1);
var import_element12 = __toESM(require_element(), 1);
var import_i18n6 = __toESM(require_i18n(), 1);
var import_jsx_runtime23 = __toESM(require_jsx_runtime(), 1);
var { ValidatedNumberControl } = unlock(import_components11.privateApis);
function toNumberOrEmpty(value) {
  if (value === "" || value === void 0) {
    return "";
  }
  const number = Number(value);
  return Number.isFinite(number) ? number : "";
}
function BetweenControls({
  value,
  onChange,
  hideLabelFromVision,
  step
}) {
  const [min = "", max = ""] = value;
  const onChangeMin = (0, import_element12.useCallback)(
    (newValue) => onChange([toNumberOrEmpty(newValue), max]),
    [onChange, max]
  );
  const onChangeMax = (0, import_element12.useCallback)(
    (newValue) => onChange([min, toNumberOrEmpty(newValue)]),
    [onChange, min]
  );
  return /* @__PURE__ */ (0, import_jsx_runtime23.jsx)(
    import_components11.BaseControl,
    {
      help: (0, import_i18n6.__)("The max. value must be greater than the min. value."),
      children: /* @__PURE__ */ (0, import_jsx_runtime23.jsxs)(import_components11.Flex, { direction: "row", gap: 4, children: [
        /* @__PURE__ */ (0, import_jsx_runtime23.jsx)(
          import_components11.__experimentalNumberControl,
          {
            label: (0, import_i18n6.__)("Min."),
            value: min,
            max: max ? Number(max) - step : void 0,
            onChange: onChangeMin,
            __next40pxDefaultSize: true,
            hideLabelFromVision,
            step
          }
        ),
        /* @__PURE__ */ (0, import_jsx_runtime23.jsx)(
          import_components11.__experimentalNumberControl,
          {
            label: (0, import_i18n6.__)("Max."),
            value: max,
            min: min ? Number(min) + step : void 0,
            onChange: onChangeMax,
            __next40pxDefaultSize: true,
            hideLabelFromVision,
            step
          }
        )
      ] })
    }
  );
}
function ValidatedNumber({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  operator,
  validity
}) {
  const decimals = field.format?.decimals ?? 0;
  const step = Math.pow(10, Math.abs(decimals) * -1);
  const { label, description, getValue, setValue, isValid: isValid2 } = field;
  const value = getValue({ item: data }) ?? "";
  const onChangeControl = (0, import_element12.useCallback)(
    (newValue) => {
      onChange(
        setValue({
          item: data,
          // Do not convert an empty string or undefined to a number,
          // otherwise there's a mismatch between the UI control (empty)
          // and the data relied by onChange (0).
          value: ["", void 0].includes(newValue) ? void 0 : Number(newValue)
        })
      );
    },
    [data, onChange, setValue]
  );
  const onChangeBetweenControls = (0, import_element12.useCallback)(
    (newValue) => {
      onChange(
        setValue({
          item: data,
          value: newValue
        })
      );
    },
    [data, onChange, setValue]
  );
  if (operator === OPERATOR_BETWEEN) {
    let valueBetween = ["", ""];
    if (Array.isArray(value) && value.length === 2 && value.every(
      (element) => typeof element === "number" || element === ""
    )) {
      valueBetween = value;
    }
    return /* @__PURE__ */ (0, import_jsx_runtime23.jsx)(
      BetweenControls,
      {
        value: valueBetween,
        onChange: onChangeBetweenControls,
        hideLabelFromVision,
        step
      }
    );
  }
  return /* @__PURE__ */ (0, import_jsx_runtime23.jsx)(
    ValidatedNumberControl,
    {
      required: !!isValid2.required,
      markWhenOptional,
      customValidity: getCustomValidity(isValid2, validity),
      label,
      help: description,
      value,
      onChange: onChangeControl,
      __next40pxDefaultSize: true,
      hideLabelFromVision,
      step,
      min: isValid2.min ? isValid2.min.constraint : void 0,
      max: isValid2.max ? isValid2.max.constraint : void 0
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/integer.mjs
var import_jsx_runtime24 = __toESM(require_jsx_runtime(), 1);
function Integer(props) {
  return /* @__PURE__ */ (0, import_jsx_runtime24.jsx)(ValidatedNumber, { ...props });
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/number.mjs
var import_jsx_runtime25 = __toESM(require_jsx_runtime(), 1);
function Number2(props) {
  return /* @__PURE__ */ (0, import_jsx_runtime25.jsx)(ValidatedNumber, { ...props });
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/radio.mjs
var import_components12 = __toESM(require_components(), 1);
var import_element13 = __toESM(require_element(), 1);
var import_jsx_runtime26 = __toESM(require_jsx_runtime(), 1);
var { ValidatedRadioControl } = unlock(import_components12.privateApis);
function Radio({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const { label, description, getValue, setValue, isValid: isValid2 } = field;
  const { elements, isLoading } = useElements({
    elements: field.elements,
    getElements: field.getElements
  });
  const value = getValue({ item: data });
  const onChangeControl = (0, import_element13.useCallback)(
    (newValue) => onChange(setValue({ item: data, value: newValue })),
    [data, onChange, setValue]
  );
  if (isLoading) {
    return /* @__PURE__ */ (0, import_jsx_runtime26.jsx)(import_components12.Spinner, {});
  }
  return /* @__PURE__ */ (0, import_jsx_runtime26.jsx)(
    ValidatedRadioControl,
    {
      required: !!field.isValid?.required,
      markWhenOptional,
      customValidity: getCustomValidity(isValid2, validity),
      label,
      help: description,
      onChange: onChangeControl,
      options: elements,
      selected: value,
      hideLabelFromVision
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/text.mjs
var import_element14 = __toESM(require_element(), 1);
var import_jsx_runtime27 = __toESM(require_jsx_runtime(), 1);
function Text({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  config,
  validity
}) {
  const { prefix, suffix } = config || {};
  return /* @__PURE__ */ (0, import_jsx_runtime27.jsx)(
    ValidatedText,
    {
      ...{
        data,
        field,
        onChange,
        hideLabelFromVision,
        markWhenOptional,
        validity,
        prefix: prefix ? (0, import_element14.createElement)(prefix) : void 0,
        suffix: suffix ? (0, import_element14.createElement)(suffix) : void 0
      }
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/toggle.mjs
var import_components13 = __toESM(require_components(), 1);
var import_element15 = __toESM(require_element(), 1);
var import_jsx_runtime28 = __toESM(require_jsx_runtime(), 1);
var { ValidatedToggleControl } = unlock(import_components13.privateApis);
function Toggle({
  field,
  onChange,
  data,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const { label, description, getValue, setValue, isValid: isValid2 } = field;
  const onChangeControl = (0, import_element15.useCallback)(() => {
    onChange(
      setValue({ item: data, value: !getValue({ item: data }) })
    );
  }, [onChange, setValue, data, getValue]);
  return /* @__PURE__ */ (0, import_jsx_runtime28.jsx)(
    ValidatedToggleControl,
    {
      required: !!isValid2.required,
      markWhenOptional,
      customValidity: getCustomValidity(isValid2, validity),
      hidden: hideLabelFromVision,
      label,
      help: description,
      checked: getValue({ item: data }),
      onChange: onChangeControl
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/textarea.mjs
var import_components14 = __toESM(require_components(), 1);
var import_element16 = __toESM(require_element(), 1);
var import_jsx_runtime29 = __toESM(require_jsx_runtime(), 1);
var { ValidatedTextareaControl } = unlock(import_components14.privateApis);
function Textarea({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  config,
  validity
}) {
  const { rows = 4 } = config || {};
  const { label, placeholder, description, setValue, isValid: isValid2 } = field;
  const value = field.getValue({ item: data });
  const onChangeControl = (0, import_element16.useCallback)(
    (newValue) => onChange(setValue({ item: data, value: newValue })),
    [data, onChange, setValue]
  );
  return /* @__PURE__ */ (0, import_jsx_runtime29.jsx)(
    ValidatedTextareaControl,
    {
      required: !!isValid2.required,
      markWhenOptional,
      customValidity: getCustomValidity(isValid2, validity),
      label,
      placeholder,
      value: value ?? "",
      help: description,
      onChange: onChangeControl,
      rows,
      minLength: isValid2.minLength ? isValid2.minLength.constraint : void 0,
      maxLength: isValid2.maxLength ? isValid2.maxLength.constraint : void 0,
      __next40pxDefaultSize: true,
      hideLabelFromVision
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/toggle-group.mjs
var import_components15 = __toESM(require_components(), 1);
var import_element17 = __toESM(require_element(), 1);
var import_jsx_runtime30 = __toESM(require_jsx_runtime(), 1);
var { ValidatedToggleGroupControl } = unlock(import_components15.privateApis);
function ToggleGroup({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const { getValue, setValue, isValid: isValid2 } = field;
  const value = getValue({ item: data });
  const onChangeControl = (0, import_element17.useCallback)(
    (newValue) => onChange(setValue({ item: data, value: newValue })),
    [data, onChange, setValue]
  );
  const { elements, isLoading } = useElements({
    elements: field.elements,
    getElements: field.getElements
  });
  if (isLoading) {
    return /* @__PURE__ */ (0, import_jsx_runtime30.jsx)(import_components15.Spinner, {});
  }
  if (elements.length === 0) {
    return null;
  }
  const selectedOption = elements.find((el) => el.value === value);
  return /* @__PURE__ */ (0, import_jsx_runtime30.jsx)(
    ValidatedToggleGroupControl,
    {
      required: !!field.isValid?.required,
      markWhenOptional,
      customValidity: getCustomValidity(isValid2, validity),
      __next40pxDefaultSize: true,
      isBlock: true,
      label: field.label,
      help: selectedOption?.description || field.description,
      onChange: onChangeControl,
      value,
      hideLabelFromVision,
      children: elements.map((el) => /* @__PURE__ */ (0, import_jsx_runtime30.jsx)(
        import_components15.__experimentalToggleGroupControlOption,
        {
          label: el.label,
          value: el.value
        },
        el.value
      ))
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/array.mjs
var import_components16 = __toESM(require_components(), 1);
var import_element18 = __toESM(require_element(), 1);
var import_jsx_runtime31 = __toESM(require_jsx_runtime(), 1);
var { ValidatedFormTokenField } = unlock(import_components16.privateApis);
function ArrayControl({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const { label, placeholder, getValue, setValue, isValid: isValid2 } = field;
  const value = getValue({ item: data });
  const { elements, isLoading } = useElements({
    elements: field.elements,
    getElements: field.getElements
  });
  const arrayValueAsElements = (0, import_element18.useMemo)(
    () => Array.isArray(value) ? value.map((token) => {
      const element = elements?.find(
        (suggestion) => suggestion.value === token
      );
      return element || { value: token, label: token };
    }) : [],
    [value, elements]
  );
  const onChangeControl = (0, import_element18.useCallback)(
    (tokens) => {
      const valueTokens = tokens.map((token) => {
        if (typeof token === "object" && "value" in token) {
          return token.value;
        }
        return token;
      });
      onChange(setValue({ item: data, value: valueTokens }));
    },
    [onChange, setValue, data]
  );
  if (isLoading) {
    return /* @__PURE__ */ (0, import_jsx_runtime31.jsx)(import_components16.Spinner, {});
  }
  return /* @__PURE__ */ (0, import_jsx_runtime31.jsx)(
    ValidatedFormTokenField,
    {
      required: !!isValid2?.required,
      markWhenOptional,
      customValidity: getCustomValidity(isValid2, validity),
      label: hideLabelFromVision ? void 0 : label,
      value: arrayValueAsElements,
      onChange: onChangeControl,
      placeholder,
      suggestions: elements?.map((element) => element.value),
      __experimentalValidateInput: (token) => {
        if (field.isValid?.elements && elements) {
          return elements.some(
            (element) => element.value === token || element.label === token
          );
        }
        return true;
      },
      __experimentalExpandOnFocus: elements && elements.length > 0,
      __experimentalShowHowTo: !field.isValid?.elements,
      displayTransform: (token) => {
        if (typeof token === "object" && "label" in token) {
          return token.label;
        }
        if (typeof token === "string" && elements) {
          const element = elements.find(
            (el) => el.value === token
          );
          return element?.label || token;
        }
        return token;
      },
      __experimentalRenderItem: ({ item }) => {
        if (typeof item === "string" && elements) {
          const element = elements.find(
            (el) => el.value === item
          );
          return /* @__PURE__ */ (0, import_jsx_runtime31.jsx)("span", { children: element?.label || item });
        }
        return /* @__PURE__ */ (0, import_jsx_runtime31.jsx)("span", { children: item });
      }
    }
  );
}

// node_modules/colord/index.mjs
var r2 = { grad: 0.9, turn: 360, rad: 360 / (2 * Math.PI) };
var t = function(r3) {
  return "string" == typeof r3 ? r3.length > 0 : "number" == typeof r3;
};
var n = function(r3, t2, n2) {
  return void 0 === t2 && (t2 = 0), void 0 === n2 && (n2 = Math.pow(10, t2)), Math.round(n2 * r3) / n2 + 0;
};
var e = function(r3, t2, n2) {
  return void 0 === t2 && (t2 = 0), void 0 === n2 && (n2 = 1), r3 > n2 ? n2 : r3 > t2 ? r3 : t2;
};
var u = function(r3) {
  return (r3 = isFinite(r3) ? r3 % 360 : 0) > 0 ? r3 : r3 + 360;
};
var a = function(r3) {
  return { r: e(r3.r, 0, 255), g: e(r3.g, 0, 255), b: e(r3.b, 0, 255), a: e(r3.a) };
};
var o = function(r3) {
  return { r: n(r3.r), g: n(r3.g), b: n(r3.b), a: n(r3.a, 3) };
};
var i = /^#([0-9a-f]{3,8})$/i;
var s = function(r3) {
  var t2 = r3.toString(16);
  return t2.length < 2 ? "0" + t2 : t2;
};
var h = function(r3) {
  var t2 = r3.r, n2 = r3.g, e2 = r3.b, u2 = r3.a, a2 = Math.max(t2, n2, e2), o2 = a2 - Math.min(t2, n2, e2), i2 = o2 ? a2 === t2 ? (n2 - e2) / o2 : a2 === n2 ? 2 + (e2 - t2) / o2 : 4 + (t2 - n2) / o2 : 0;
  return { h: 60 * (i2 < 0 ? i2 + 6 : i2), s: a2 ? o2 / a2 * 100 : 0, v: a2 / 255 * 100, a: u2 };
};
var b = function(r3) {
  var t2 = r3.h, n2 = r3.s, e2 = r3.v, u2 = r3.a;
  t2 = t2 / 360 * 6, n2 /= 100, e2 /= 100;
  var a2 = Math.floor(t2), o2 = e2 * (1 - n2), i2 = e2 * (1 - (t2 - a2) * n2), s2 = e2 * (1 - (1 - t2 + a2) * n2), h2 = a2 % 6;
  return { r: 255 * [e2, i2, o2, o2, s2, e2][h2], g: 255 * [s2, e2, e2, i2, o2, o2][h2], b: 255 * [o2, o2, s2, e2, e2, i2][h2], a: u2 };
};
var g = function(r3) {
  return { h: u(r3.h), s: e(r3.s, 0, 100), l: e(r3.l, 0, 100), a: e(r3.a) };
};
var d = function(r3) {
  return { h: n(r3.h), s: n(r3.s), l: n(r3.l), a: n(r3.a, 3) };
};
var f = function(r3) {
  return b((n2 = (t2 = r3).s, { h: t2.h, s: (n2 *= ((e2 = t2.l) < 50 ? e2 : 100 - e2) / 100) > 0 ? 2 * n2 / (e2 + n2) * 100 : 0, v: e2 + n2, a: t2.a }));
  var t2, n2, e2;
};
var c = function(r3) {
  return { h: (t2 = h(r3)).h, s: (u2 = (200 - (n2 = t2.s)) * (e2 = t2.v) / 100) > 0 && u2 < 200 ? n2 * e2 / 100 / (u2 <= 100 ? u2 : 200 - u2) * 100 : 0, l: u2 / 2, a: t2.a };
  var t2, n2, e2, u2;
};
var l = /^hsla?\(\s*([+-]?\d*\.?\d+)(deg|rad|grad|turn)?\s*,\s*([+-]?\d*\.?\d+)%\s*,\s*([+-]?\d*\.?\d+)%\s*(?:,\s*([+-]?\d*\.?\d+)(%)?\s*)?\)$/i;
var p = /^hsla?\(\s*([+-]?\d*\.?\d+)(deg|rad|grad|turn)?\s+([+-]?\d*\.?\d+)%\s+([+-]?\d*\.?\d+)%\s*(?:\/\s*([+-]?\d*\.?\d+)(%)?\s*)?\)$/i;
var v = /^rgba?\(\s*([+-]?\d*\.?\d+)(%)?\s*,\s*([+-]?\d*\.?\d+)(%)?\s*,\s*([+-]?\d*\.?\d+)(%)?\s*(?:,\s*([+-]?\d*\.?\d+)(%)?\s*)?\)$/i;
var m = /^rgba?\(\s*([+-]?\d*\.?\d+)(%)?\s+([+-]?\d*\.?\d+)(%)?\s+([+-]?\d*\.?\d+)(%)?\s*(?:\/\s*([+-]?\d*\.?\d+)(%)?\s*)?\)$/i;
var y = { string: [[function(r3) {
  var t2 = i.exec(r3);
  return t2 ? (r3 = t2[1]).length <= 4 ? { r: parseInt(r3[0] + r3[0], 16), g: parseInt(r3[1] + r3[1], 16), b: parseInt(r3[2] + r3[2], 16), a: 4 === r3.length ? n(parseInt(r3[3] + r3[3], 16) / 255, 2) : 1 } : 6 === r3.length || 8 === r3.length ? { r: parseInt(r3.substr(0, 2), 16), g: parseInt(r3.substr(2, 2), 16), b: parseInt(r3.substr(4, 2), 16), a: 8 === r3.length ? n(parseInt(r3.substr(6, 2), 16) / 255, 2) : 1 } : null : null;
}, "hex"], [function(r3) {
  var t2 = v.exec(r3) || m.exec(r3);
  return t2 ? t2[2] !== t2[4] || t2[4] !== t2[6] ? null : a({ r: Number(t2[1]) / (t2[2] ? 100 / 255 : 1), g: Number(t2[3]) / (t2[4] ? 100 / 255 : 1), b: Number(t2[5]) / (t2[6] ? 100 / 255 : 1), a: void 0 === t2[7] ? 1 : Number(t2[7]) / (t2[8] ? 100 : 1) }) : null;
}, "rgb"], [function(t2) {
  var n2 = l.exec(t2) || p.exec(t2);
  if (!n2) return null;
  var e2, u2, a2 = g({ h: (e2 = n2[1], u2 = n2[2], void 0 === u2 && (u2 = "deg"), Number(e2) * (r2[u2] || 1)), s: Number(n2[3]), l: Number(n2[4]), a: void 0 === n2[5] ? 1 : Number(n2[5]) / (n2[6] ? 100 : 1) });
  return f(a2);
}, "hsl"]], object: [[function(r3) {
  var n2 = r3.r, e2 = r3.g, u2 = r3.b, o2 = r3.a, i2 = void 0 === o2 ? 1 : o2;
  return t(n2) && t(e2) && t(u2) ? a({ r: Number(n2), g: Number(e2), b: Number(u2), a: Number(i2) }) : null;
}, "rgb"], [function(r3) {
  var n2 = r3.h, e2 = r3.s, u2 = r3.l, a2 = r3.a, o2 = void 0 === a2 ? 1 : a2;
  if (!t(n2) || !t(e2) || !t(u2)) return null;
  var i2 = g({ h: Number(n2), s: Number(e2), l: Number(u2), a: Number(o2) });
  return f(i2);
}, "hsl"], [function(r3) {
  var n2 = r3.h, a2 = r3.s, o2 = r3.v, i2 = r3.a, s2 = void 0 === i2 ? 1 : i2;
  if (!t(n2) || !t(a2) || !t(o2)) return null;
  var h2 = function(r4) {
    return { h: u(r4.h), s: e(r4.s, 0, 100), v: e(r4.v, 0, 100), a: e(r4.a) };
  }({ h: Number(n2), s: Number(a2), v: Number(o2), a: Number(s2) });
  return b(h2);
}, "hsv"]] };
var N = function(r3, t2) {
  for (var n2 = 0; n2 < t2.length; n2++) {
    var e2 = t2[n2][0](r3);
    if (e2) return [e2, t2[n2][1]];
  }
  return [null, void 0];
};
var x = function(r3) {
  return "string" == typeof r3 ? N(r3.trim(), y.string) : "object" == typeof r3 && null !== r3 ? N(r3, y.object) : [null, void 0];
};
var M = function(r3, t2) {
  var n2 = c(r3);
  return { h: n2.h, s: e(n2.s + 100 * t2, 0, 100), l: n2.l, a: n2.a };
};
var H = function(r3) {
  return (299 * r3.r + 587 * r3.g + 114 * r3.b) / 1e3 / 255;
};
var $ = function(r3, t2) {
  var n2 = c(r3);
  return { h: n2.h, s: n2.s, l: e(n2.l + 100 * t2, 0, 100), a: n2.a };
};
var j = function() {
  function r3(r4) {
    this.parsed = x(r4)[0], this.rgba = this.parsed || { r: 0, g: 0, b: 0, a: 1 };
  }
  return r3.prototype.isValid = function() {
    return null !== this.parsed;
  }, r3.prototype.brightness = function() {
    return n(H(this.rgba), 2);
  }, r3.prototype.isDark = function() {
    return H(this.rgba) < 0.5;
  }, r3.prototype.isLight = function() {
    return H(this.rgba) >= 0.5;
  }, r3.prototype.toHex = function() {
    return r4 = o(this.rgba), t2 = r4.r, e2 = r4.g, u2 = r4.b, i2 = (a2 = r4.a) < 1 ? s(n(255 * a2)) : "", "#" + s(t2) + s(e2) + s(u2) + i2;
    var r4, t2, e2, u2, a2, i2;
  }, r3.prototype.toRgb = function() {
    return o(this.rgba);
  }, r3.prototype.toRgbString = function() {
    return r4 = o(this.rgba), t2 = r4.r, n2 = r4.g, e2 = r4.b, (u2 = r4.a) < 1 ? "rgba(" + t2 + ", " + n2 + ", " + e2 + ", " + u2 + ")" : "rgb(" + t2 + ", " + n2 + ", " + e2 + ")";
    var r4, t2, n2, e2, u2;
  }, r3.prototype.toHsl = function() {
    return d(c(this.rgba));
  }, r3.prototype.toHslString = function() {
    return r4 = d(c(this.rgba)), t2 = r4.h, n2 = r4.s, e2 = r4.l, (u2 = r4.a) < 1 ? "hsla(" + t2 + ", " + n2 + "%, " + e2 + "%, " + u2 + ")" : "hsl(" + t2 + ", " + n2 + "%, " + e2 + "%)";
    var r4, t2, n2, e2, u2;
  }, r3.prototype.toHsv = function() {
    return r4 = h(this.rgba), { h: n(r4.h), s: n(r4.s), v: n(r4.v), a: n(r4.a, 3) };
    var r4;
  }, r3.prototype.invert = function() {
    return w({ r: 255 - (r4 = this.rgba).r, g: 255 - r4.g, b: 255 - r4.b, a: r4.a });
    var r4;
  }, r3.prototype.saturate = function(r4) {
    return void 0 === r4 && (r4 = 0.1), w(M(this.rgba, r4));
  }, r3.prototype.desaturate = function(r4) {
    return void 0 === r4 && (r4 = 0.1), w(M(this.rgba, -r4));
  }, r3.prototype.grayscale = function() {
    return w(M(this.rgba, -1));
  }, r3.prototype.lighten = function(r4) {
    return void 0 === r4 && (r4 = 0.1), w($(this.rgba, r4));
  }, r3.prototype.darken = function(r4) {
    return void 0 === r4 && (r4 = 0.1), w($(this.rgba, -r4));
  }, r3.prototype.rotate = function(r4) {
    return void 0 === r4 && (r4 = 15), this.hue(this.hue() + r4);
  }, r3.prototype.alpha = function(r4) {
    return "number" == typeof r4 ? w({ r: (t2 = this.rgba).r, g: t2.g, b: t2.b, a: r4 }) : n(this.rgba.a, 3);
    var t2;
  }, r3.prototype.hue = function(r4) {
    var t2 = c(this.rgba);
    return "number" == typeof r4 ? w({ h: r4, s: t2.s, l: t2.l, a: t2.a }) : n(t2.h);
  }, r3.prototype.isEqual = function(r4) {
    return this.toHex() === w(r4).toHex();
  }, r3;
}();
var w = function(r3) {
  return r3 instanceof j ? r3 : new j(r3);
};

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/color.mjs
var import_components17 = __toESM(require_components(), 1);
var import_element19 = __toESM(require_element(), 1);
var import_i18n7 = __toESM(require_i18n(), 1);
var import_jsx_runtime32 = __toESM(require_jsx_runtime(), 1);
var { ValidatedInputControl: ValidatedInputControl3 } = unlock(import_components17.privateApis);
var ColorPickerDropdown = ({
  color,
  onColorChange
}) => {
  const validColor = color && w(color).isValid() ? color : "#ffffff";
  return /* @__PURE__ */ (0, import_jsx_runtime32.jsx)(
    import_components17.Dropdown,
    {
      className: "dataviews-controls__color-picker-dropdown",
      popoverProps: { resize: false },
      renderToggle: ({ onToggle }) => /* @__PURE__ */ (0, import_jsx_runtime32.jsx)(
        import_components17.Button,
        {
          onClick: onToggle,
          "aria-label": (0, import_i18n7.__)("Open color picker"),
          size: "small",
          icon: () => /* @__PURE__ */ (0, import_jsx_runtime32.jsx)(import_components17.ColorIndicator, { colorValue: validColor })
        }
      ),
      renderContent: () => /* @__PURE__ */ (0, import_jsx_runtime32.jsx)(import_components17.__experimentalDropdownContentWrapper, { paddingSize: "none", children: /* @__PURE__ */ (0, import_jsx_runtime32.jsx)(
        import_components17.ColorPicker,
        {
          color: validColor,
          onChange: onColorChange,
          enableAlpha: true
        }
      ) })
    }
  );
};
function Color({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const { label, placeholder, description, setValue, isValid: isValid2 } = field;
  const value = field.getValue({ item: data }) || "";
  const handleColorChange = (0, import_element19.useCallback)(
    (newColor) => {
      onChange(setValue({ item: data, value: newColor }));
    },
    [data, onChange, setValue]
  );
  const handleInputChange = (0, import_element19.useCallback)(
    (newValue) => {
      onChange(setValue({ item: data, value: newValue || "" }));
    },
    [data, onChange, setValue]
  );
  return /* @__PURE__ */ (0, import_jsx_runtime32.jsx)(
    ValidatedInputControl3,
    {
      required: !!field.isValid?.required,
      markWhenOptional,
      customValidity: getCustomValidity(isValid2, validity),
      label,
      placeholder,
      value,
      help: description,
      onChange: handleInputChange,
      hideLabelFromVision,
      type: "text",
      prefix: /* @__PURE__ */ (0, import_jsx_runtime32.jsx)(import_components17.__experimentalInputControlPrefixWrapper, { variant: "control", children: /* @__PURE__ */ (0, import_jsx_runtime32.jsx)(
        ColorPickerDropdown,
        {
          color: value,
          onColorChange: handleColorChange
        }
      ) })
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/password.mjs
var import_components18 = __toESM(require_components(), 1);
var import_element20 = __toESM(require_element(), 1);
var import_i18n8 = __toESM(require_i18n(), 1);
var import_jsx_runtime33 = __toESM(require_jsx_runtime(), 1);
function Password({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const [isVisible, setIsVisible] = (0, import_element20.useState)(false);
  const toggleVisibility = (0, import_element20.useCallback)(() => {
    setIsVisible((prev) => !prev);
  }, []);
  return /* @__PURE__ */ (0, import_jsx_runtime33.jsx)(
    ValidatedText,
    {
      ...{
        data,
        field,
        onChange,
        hideLabelFromVision,
        markWhenOptional,
        validity,
        type: isVisible ? "text" : "password",
        suffix: /* @__PURE__ */ (0, import_jsx_runtime33.jsx)(import_components18.__experimentalInputControlSuffixWrapper, { variant: "control", children: /* @__PURE__ */ (0, import_jsx_runtime33.jsx)(
          import_components18.Button,
          {
            icon: isVisible ? unseen_default : seen_default,
            onClick: toggleVisibility,
            size: "small",
            label: isVisible ? (0, import_i18n8.__)("Hide password") : (0, import_i18n8.__)("Show password")
          }
        ) })
      }
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/has-elements.mjs
function hasElements(field) {
  return Array.isArray(field.elements) && field.elements.length > 0 || typeof field.getElements === "function";
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-controls/index.mjs
var import_jsx_runtime34 = __toESM(require_jsx_runtime(), 1);
var FORM_CONTROLS = {
  adaptiveSelect: AdaptiveSelect,
  array: ArrayControl,
  checkbox: Checkbox,
  color: Color,
  combobox: Combobox,
  datetime: DateTime,
  date: DateControl,
  email: Email,
  telephone: Telephone,
  url: Url,
  integer: Integer,
  number: Number2,
  password: Password,
  radio: Radio,
  select: Select,
  text: Text,
  toggle: Toggle,
  textarea: Textarea,
  toggleGroup: ToggleGroup
};
function isEditConfig(value) {
  return value && typeof value === "object" && typeof value.control === "string";
}
function createConfiguredControl(config) {
  const { control, ...controlConfig } = config;
  const BaseControlType = getControlByType(control);
  if (BaseControlType === null) {
    return null;
  }
  return function ConfiguredControl(props) {
    return /* @__PURE__ */ (0, import_jsx_runtime34.jsx)(BaseControlType, { ...props, config: controlConfig });
  };
}
function getControl(field, fallback) {
  if (typeof field.Edit === "function") {
    return field.Edit;
  }
  if (typeof field.Edit === "string") {
    return getControlByType(field.Edit);
  }
  if (isEditConfig(field.Edit)) {
    return createConfiguredControl(field.Edit);
  }
  if (hasElements(field) && field.type !== "array") {
    return getControlByType("adaptiveSelect");
  }
  if (fallback === null) {
    return null;
  }
  return getControlByType(fallback);
}
function getControlByType(type) {
  if (Object.keys(FORM_CONTROLS).includes(type)) {
    return FORM_CONTROLS[type];
  }
  return null;
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/get-filter-by.mjs
function getFilterBy(field, defaultOperators, validOperators) {
  if (field.filterBy === false) {
    return false;
  }
  const operators = field.filterBy?.operators?.filter(
    (op) => validOperators.includes(op)
  ) ?? defaultOperators;
  if (operators.length === 0) {
    return false;
  }
  return {
    isPrimary: !!field.filterBy?.isPrimary,
    operators
  };
}
var get_filter_by_default = getFilterBy;

// node_modules/@wordpress/dataviews/build-module/field-types/utils/get-value-from-id.mjs
var getValueFromId = (id) => ({ item }) => {
  const path = id.split(".");
  let value = item;
  for (const segment of path) {
    if (value.hasOwnProperty(segment)) {
      value = value[segment];
    } else {
      value = void 0;
    }
  }
  return value;
};
var get_value_from_id_default = getValueFromId;

// node_modules/@wordpress/dataviews/build-module/field-types/utils/set-value-from-id.mjs
var setValueFromId = (id) => ({ value }) => {
  const path = id.split(".");
  const result = {};
  let current = result;
  for (const segment of path.slice(0, -1)) {
    current[segment] = {};
    current = current[segment];
  }
  current[path.at(-1)] = value;
  return result;
};
var set_value_from_id_default = setValueFromId;

// node_modules/@wordpress/dataviews/build-module/field-types/email.mjs
var import_i18n9 = __toESM(require_i18n(), 1);

// node_modules/@wordpress/dataviews/build-module/field-types/utils/render-from-elements.mjs
function RenderFromElements({
  item,
  field
}) {
  const { elements, isLoading } = useElements({
    elements: field.elements,
    getElements: field.getElements
  });
  const value = field.getValue({ item });
  if (isLoading) {
    return value;
  }
  if (elements.length === 0) {
    return value;
  }
  return elements?.find((element) => element.value === value)?.label || field.getValue({ item });
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/render-default.mjs
var import_jsx_runtime35 = __toESM(require_jsx_runtime(), 1);
function render({
  item,
  field
}) {
  if (field.hasElements) {
    return /* @__PURE__ */ (0, import_jsx_runtime35.jsx)(RenderFromElements, { item, field });
  }
  return field.getValueFormatted({ item, field });
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/sort-text.mjs
var sort_text_default = (a2, b2, direction) => {
  return direction === "asc" ? a2.localeCompare(b2) : b2.localeCompare(a2);
};

// node_modules/@wordpress/dataviews/build-module/field-types/utils/is-valid-required.mjs
function isValidRequired(item, field) {
  const value = field.getValue({ item });
  return ![void 0, "", null].includes(value);
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/is-valid-min-length.mjs
function isValidMinLength(item, field) {
  if (typeof field.isValid.minLength?.constraint !== "number") {
    return false;
  }
  const value = field.getValue({ item });
  if ([void 0, "", null].includes(value)) {
    return true;
  }
  return String(value).length >= field.isValid.minLength.constraint;
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/is-valid-max-length.mjs
function isValidMaxLength(item, field) {
  if (typeof field.isValid.maxLength?.constraint !== "number") {
    return false;
  }
  const value = field.getValue({ item });
  if ([void 0, "", null].includes(value)) {
    return true;
  }
  return String(value).length <= field.isValid.maxLength.constraint;
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/is-valid-pattern.mjs
function isValidPattern(item, field) {
  if (field.isValid.pattern?.constraint === void 0) {
    return true;
  }
  try {
    const regexp = new RegExp(field.isValid.pattern.constraint);
    const value = field.getValue({ item });
    if ([void 0, "", null].includes(value)) {
      return true;
    }
    return regexp.test(String(value));
  } catch {
    return false;
  }
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/is-valid-elements.mjs
function isValidElements(item, field) {
  const elements = field.elements ?? [];
  const validValues = elements.map((el) => el.value);
  if (validValues.length === 0) {
    return true;
  }
  const value = field.getValue({ item });
  return [].concat(value).every((v2) => validValues.includes(v2));
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/get-value-formatted-default.mjs
function getValueFormatted({
  item,
  field
}) {
  return field.getValue({ item });
}
var get_value_formatted_default_default = getValueFormatted;

// node_modules/@wordpress/dataviews/build-module/field-types/email.mjs
var emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
function isValidCustom(item, field) {
  const value = field.getValue({ item });
  if (![void 0, "", null].includes(value) && !emailRegex.test(value)) {
    return (0, import_i18n9.__)("Value must be a valid email address.");
  }
  return null;
}
var email_default = {
  type: "email",
  render,
  Edit: "email",
  sort: sort_text_default,
  enableSorting: true,
  enableGlobalSearch: false,
  defaultOperators: [OPERATOR_IS_ANY, OPERATOR_IS_NONE],
  validOperators: [
    OPERATOR_IS,
    OPERATOR_IS_NOT,
    OPERATOR_CONTAINS,
    OPERATOR_NOT_CONTAINS,
    OPERATOR_STARTS_WITH,
    // Multiple selection
    OPERATOR_IS_ANY,
    OPERATOR_IS_NONE,
    OPERATOR_IS_ALL,
    OPERATOR_IS_NOT_ALL
  ],
  format: {},
  getValueFormatted: get_value_formatted_default_default,
  validate: {
    required: isValidRequired,
    pattern: isValidPattern,
    minLength: isValidMinLength,
    maxLength: isValidMaxLength,
    elements: isValidElements,
    custom: isValidCustom
  }
};

// node_modules/@wordpress/dataviews/build-module/field-types/integer.mjs
var import_i18n10 = __toESM(require_i18n(), 1);

// node_modules/@wordpress/dataviews/build-module/field-types/utils/sort-number.mjs
var sort_number_default = (a2, b2, direction) => {
  return direction === "asc" ? a2 - b2 : b2 - a2;
};

// node_modules/@wordpress/dataviews/build-module/field-types/utils/is-valid-min.mjs
function isValidMin(item, field) {
  if (typeof field.isValid.min?.constraint !== "number") {
    return false;
  }
  const value = field.getValue({ item });
  if ([void 0, "", null].includes(value)) {
    return true;
  }
  return Number(value) >= field.isValid.min.constraint;
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/is-valid-max.mjs
function isValidMax(item, field) {
  if (typeof field.isValid.max?.constraint !== "number") {
    return false;
  }
  const value = field.getValue({ item });
  if ([void 0, "", null].includes(value)) {
    return true;
  }
  return Number(value) <= field.isValid.max.constraint;
}

// node_modules/@wordpress/dataviews/build-module/field-types/integer.mjs
var format2 = {
  separatorThousand: ","
};
function getValueFormatted2({
  item,
  field
}) {
  let value = field.getValue({ item });
  if (value === null || value === void 0) {
    return "";
  }
  value = Number(value);
  if (!Number.isFinite(value)) {
    return String(value);
  }
  let formatInteger;
  if (field.type !== "integer") {
    formatInteger = format2;
  } else {
    formatInteger = field.format;
  }
  const { separatorThousand } = formatInteger;
  const integerValue = Math.trunc(value);
  if (!separatorThousand) {
    return String(integerValue);
  }
  return String(integerValue).replace(
    /\B(?=(\d{3})+(?!\d))/g,
    separatorThousand
  );
}
function isValidCustom2(item, field) {
  const value = field.getValue({ item });
  if (![void 0, "", null].includes(value) && !Number.isInteger(value)) {
    return (0, import_i18n10.__)("Value must be an integer.");
  }
  return null;
}
var integer_default = {
  type: "integer",
  render,
  Edit: "integer",
  sort: sort_number_default,
  enableSorting: true,
  enableGlobalSearch: false,
  defaultOperators: [
    OPERATOR_IS,
    OPERATOR_IS_NOT,
    OPERATOR_LESS_THAN,
    OPERATOR_GREATER_THAN,
    OPERATOR_LESS_THAN_OR_EQUAL,
    OPERATOR_GREATER_THAN_OR_EQUAL,
    OPERATOR_BETWEEN
  ],
  validOperators: [
    // Single-selection
    OPERATOR_IS,
    OPERATOR_IS_NOT,
    OPERATOR_LESS_THAN,
    OPERATOR_GREATER_THAN,
    OPERATOR_LESS_THAN_OR_EQUAL,
    OPERATOR_GREATER_THAN_OR_EQUAL,
    OPERATOR_BETWEEN,
    // Multiple-selection
    OPERATOR_IS_ANY,
    OPERATOR_IS_NONE,
    OPERATOR_IS_ALL,
    OPERATOR_IS_NOT_ALL
  ],
  format: format2,
  getValueFormatted: getValueFormatted2,
  validate: {
    required: isValidRequired,
    min: isValidMin,
    max: isValidMax,
    elements: isValidElements,
    custom: isValidCustom2
  }
};

// node_modules/@wordpress/dataviews/build-module/field-types/number.mjs
var import_i18n11 = __toESM(require_i18n(), 1);
var format3 = {
  separatorThousand: ",",
  separatorDecimal: ".",
  decimals: 2
};
function getValueFormatted3({
  item,
  field
}) {
  let value = field.getValue({ item });
  if (value === null || value === void 0) {
    return "";
  }
  value = Number(value);
  if (!Number.isFinite(value)) {
    return String(value);
  }
  let formatNumber;
  if (field.type !== "number") {
    formatNumber = format3;
  } else {
    formatNumber = field.format;
  }
  const { separatorThousand, separatorDecimal, decimals } = formatNumber;
  const fixedValue = value.toFixed(decimals);
  const [integerPart, decimalPart] = fixedValue.split(".");
  const formattedInteger = separatorThousand ? integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, separatorThousand) : integerPart;
  return decimals === 0 ? formattedInteger : formattedInteger + separatorDecimal + decimalPart;
}
function isEmpty(value) {
  return value === "" || value === void 0 || value === null;
}
function isValidCustom3(item, field) {
  const value = field.getValue({ item });
  if (!isEmpty(value) && !Number.isFinite(value)) {
    return (0, import_i18n11.__)("Value must be a number.");
  }
  return null;
}
var number_default = {
  type: "number",
  render,
  Edit: "number",
  sort: sort_number_default,
  enableSorting: true,
  enableGlobalSearch: false,
  defaultOperators: [
    OPERATOR_IS,
    OPERATOR_IS_NOT,
    OPERATOR_LESS_THAN,
    OPERATOR_GREATER_THAN,
    OPERATOR_LESS_THAN_OR_EQUAL,
    OPERATOR_GREATER_THAN_OR_EQUAL,
    OPERATOR_BETWEEN
  ],
  validOperators: [
    // Single-selection
    OPERATOR_IS,
    OPERATOR_IS_NOT,
    OPERATOR_LESS_THAN,
    OPERATOR_GREATER_THAN,
    OPERATOR_LESS_THAN_OR_EQUAL,
    OPERATOR_GREATER_THAN_OR_EQUAL,
    OPERATOR_BETWEEN,
    // Multiple-selection
    OPERATOR_IS_ANY,
    OPERATOR_IS_NONE,
    OPERATOR_IS_ALL,
    OPERATOR_IS_NOT_ALL
  ],
  format: format3,
  getValueFormatted: getValueFormatted3,
  validate: {
    required: isValidRequired,
    min: isValidMin,
    max: isValidMax,
    elements: isValidElements,
    custom: isValidCustom3
  }
};

// node_modules/@wordpress/dataviews/build-module/field-types/text.mjs
var text_default = {
  type: "text",
  render,
  Edit: "text",
  sort: sort_text_default,
  enableSorting: true,
  enableGlobalSearch: false,
  defaultOperators: [OPERATOR_IS_ANY, OPERATOR_IS_NONE],
  validOperators: [
    // Single selection
    OPERATOR_IS,
    OPERATOR_IS_NOT,
    OPERATOR_CONTAINS,
    OPERATOR_NOT_CONTAINS,
    OPERATOR_STARTS_WITH,
    // Multiple selection
    OPERATOR_IS_ANY,
    OPERATOR_IS_NONE,
    OPERATOR_IS_ALL,
    OPERATOR_IS_NOT_ALL
  ],
  format: {},
  getValueFormatted: get_value_formatted_default_default,
  validate: {
    required: isValidRequired,
    pattern: isValidPattern,
    minLength: isValidMinLength,
    maxLength: isValidMaxLength,
    elements: isValidElements
  }
};

// node_modules/@wordpress/dataviews/build-module/field-types/datetime.mjs
var import_date6 = __toESM(require_date(), 1);
var format4 = {
  datetime: (0, import_date6.getSettings)().formats.datetime,
  weekStartsOn: (0, import_date6.getSettings)().l10n.startOfWeek
};
function getValueFormatted4({
  item,
  field
}) {
  const value = field.getValue({ item });
  if (["", void 0, null].includes(value)) {
    return "";
  }
  let formatDatetime;
  if (field.type !== "datetime") {
    formatDatetime = format4;
  } else {
    formatDatetime = field.format;
  }
  return (0, import_date6.dateI18n)(formatDatetime.datetime, (0, import_date6.getDate)(value));
}
var sort = (a2, b2, direction) => {
  const timeA = new Date(a2).getTime();
  const timeB = new Date(b2).getTime();
  return direction === "asc" ? timeA - timeB : timeB - timeA;
};
var datetime_default = {
  type: "datetime",
  render,
  Edit: "datetime",
  sort,
  enableSorting: true,
  enableGlobalSearch: false,
  defaultOperators: [
    OPERATOR_ON,
    OPERATOR_NOT_ON,
    OPERATOR_BEFORE,
    OPERATOR_AFTER,
    OPERATOR_BEFORE_INC,
    OPERATOR_AFTER_INC,
    OPERATOR_IN_THE_PAST,
    OPERATOR_OVER
  ],
  validOperators: [
    OPERATOR_ON,
    OPERATOR_NOT_ON,
    OPERATOR_BEFORE,
    OPERATOR_AFTER,
    OPERATOR_BEFORE_INC,
    OPERATOR_AFTER_INC,
    OPERATOR_IN_THE_PAST,
    OPERATOR_OVER
  ],
  format: format4,
  getValueFormatted: getValueFormatted4,
  validate: {
    required: isValidRequired,
    elements: isValidElements
  }
};

// node_modules/@wordpress/dataviews/build-module/field-types/date.mjs
var import_date7 = __toESM(require_date(), 1);
var format5 = {
  date: (0, import_date7.getSettings)().formats.date,
  weekStartsOn: (0, import_date7.getSettings)().l10n.startOfWeek
};
function getValueFormatted5({
  item,
  field
}) {
  const value = field.getValue({ item });
  if (["", void 0, null].includes(value)) {
    return "";
  }
  let formatDate2;
  if (field.type !== "date") {
    formatDate2 = format5;
  } else {
    formatDate2 = field.format;
  }
  return (0, import_date7.dateI18n)(formatDate2.date, (0, import_date7.getDate)(value));
}
var sort2 = (a2, b2, direction) => {
  const timeA = new Date(a2).getTime();
  const timeB = new Date(b2).getTime();
  return direction === "asc" ? timeA - timeB : timeB - timeA;
};
var date_default = {
  type: "date",
  render,
  Edit: "date",
  sort: sort2,
  enableSorting: true,
  enableGlobalSearch: false,
  defaultOperators: [
    OPERATOR_ON,
    OPERATOR_NOT_ON,
    OPERATOR_BEFORE,
    OPERATOR_AFTER,
    OPERATOR_BEFORE_INC,
    OPERATOR_AFTER_INC,
    OPERATOR_IN_THE_PAST,
    OPERATOR_OVER,
    OPERATOR_BETWEEN
  ],
  validOperators: [
    OPERATOR_ON,
    OPERATOR_NOT_ON,
    OPERATOR_BEFORE,
    OPERATOR_AFTER,
    OPERATOR_BEFORE_INC,
    OPERATOR_AFTER_INC,
    OPERATOR_IN_THE_PAST,
    OPERATOR_OVER,
    OPERATOR_BETWEEN
  ],
  format: format5,
  getValueFormatted: getValueFormatted5,
  validate: {
    required: isValidRequired,
    elements: isValidElements
  }
};

// node_modules/@wordpress/dataviews/build-module/field-types/boolean.mjs
var import_i18n12 = __toESM(require_i18n(), 1);

// node_modules/@wordpress/dataviews/build-module/field-types/utils/is-valid-required-for-bool.mjs
function isValidRequiredForBool(item, field) {
  const value = field.getValue({ item });
  return value === true;
}

// node_modules/@wordpress/dataviews/build-module/field-types/boolean.mjs
function getValueFormatted6({
  item,
  field
}) {
  const value = field.getValue({ item });
  if (value === true) {
    return (0, import_i18n12.__)("True");
  }
  if (value === false) {
    return (0, import_i18n12.__)("False");
  }
  return "";
}
function isValidCustom4(item, field) {
  const value = field.getValue({ item });
  if (![void 0, "", null].includes(value) && ![true, false].includes(value)) {
    return (0, import_i18n12.__)("Value must be true, false, or undefined");
  }
  return null;
}
var sort3 = (a2, b2, direction) => {
  const boolA = Boolean(a2);
  const boolB = Boolean(b2);
  if (boolA === boolB) {
    return 0;
  }
  if (direction === "asc") {
    return boolA ? 1 : -1;
  }
  return boolA ? -1 : 1;
};
var boolean_default = {
  type: "boolean",
  render,
  Edit: "checkbox",
  sort: sort3,
  validate: {
    required: isValidRequiredForBool,
    elements: isValidElements,
    custom: isValidCustom4
  },
  enableSorting: true,
  enableGlobalSearch: false,
  defaultOperators: [OPERATOR_IS, OPERATOR_IS_NOT],
  validOperators: [OPERATOR_IS, OPERATOR_IS_NOT],
  format: {},
  getValueFormatted: getValueFormatted6
};

// node_modules/@wordpress/dataviews/build-module/field-types/media.mjs
var media_default = {
  type: "media",
  render: () => null,
  Edit: null,
  sort: () => 0,
  enableSorting: false,
  enableGlobalSearch: false,
  defaultOperators: [],
  validOperators: [],
  format: {},
  getValueFormatted: get_value_formatted_default_default,
  // cannot validate any constraint, so
  // the only available validation for the field author
  // would be providing a custom validator.
  validate: {}
};

// node_modules/@wordpress/dataviews/build-module/field-types/array.mjs
var import_i18n13 = __toESM(require_i18n(), 1);

// node_modules/@wordpress/dataviews/build-module/field-types/utils/is-valid-required-for-array.mjs
function isValidRequiredForArray(item, field) {
  const value = field.getValue({ item });
  return Array.isArray(value) && value.length > 0 && value.every(
    (element) => ![void 0, "", null].includes(element)
  );
}

// node_modules/@wordpress/dataviews/build-module/field-types/array.mjs
function getValueFormatted7({
  item,
  field
}) {
  const value = field.getValue({ item });
  const arr = Array.isArray(value) ? value : [];
  return arr.join(", ");
}
function render2({ item, field }) {
  return getValueFormatted7({ item, field });
}
function isValidCustom5(item, field) {
  const value = field.getValue({ item });
  if (![void 0, "", null].includes(value) && !Array.isArray(value)) {
    return (0, import_i18n13.__)("Value must be an array.");
  }
  if (!value.every((v2) => typeof v2 === "string")) {
    return (0, import_i18n13.__)("Every value must be a string.");
  }
  return null;
}
var sort4 = (a2, b2, direction) => {
  const arrA = Array.isArray(a2) ? a2 : [];
  const arrB = Array.isArray(b2) ? b2 : [];
  if (arrA.length !== arrB.length) {
    return direction === "asc" ? arrA.length - arrB.length : arrB.length - arrA.length;
  }
  const joinedA = arrA.join(",");
  const joinedB = arrB.join(",");
  return direction === "asc" ? joinedA.localeCompare(joinedB) : joinedB.localeCompare(joinedA);
};
var array_default = {
  type: "array",
  render: render2,
  Edit: "array",
  sort: sort4,
  enableSorting: true,
  enableGlobalSearch: false,
  defaultOperators: [OPERATOR_IS_ANY, OPERATOR_IS_NONE],
  validOperators: [
    OPERATOR_IS_ANY,
    OPERATOR_IS_NONE,
    OPERATOR_IS_ALL,
    OPERATOR_IS_NOT_ALL
  ],
  format: {},
  getValueFormatted: getValueFormatted7,
  validate: {
    required: isValidRequiredForArray,
    elements: isValidElements,
    custom: isValidCustom5
  }
};

// node_modules/@wordpress/dataviews/build-module/field-types/password.mjs
function getValueFormatted8({
  item,
  field
}) {
  return field.getValue({ item }) ? "\u2022\u2022\u2022\u2022\u2022\u2022\u2022\u2022" : "";
}
var password_default = {
  type: "password",
  render,
  Edit: "password",
  sort: () => 0,
  // Passwords should not be sortable for security reasons
  enableSorting: false,
  enableGlobalSearch: false,
  defaultOperators: [],
  validOperators: [],
  format: {},
  getValueFormatted: getValueFormatted8,
  validate: {
    required: isValidRequired,
    pattern: isValidPattern,
    minLength: isValidMinLength,
    maxLength: isValidMaxLength,
    elements: isValidElements
  }
};

// node_modules/@wordpress/dataviews/build-module/field-types/telephone.mjs
var telephone_default = {
  type: "telephone",
  render,
  Edit: "telephone",
  sort: sort_text_default,
  enableSorting: true,
  enableGlobalSearch: false,
  defaultOperators: [OPERATOR_IS_ANY, OPERATOR_IS_NONE],
  validOperators: [
    OPERATOR_IS,
    OPERATOR_IS_NOT,
    OPERATOR_CONTAINS,
    OPERATOR_NOT_CONTAINS,
    OPERATOR_STARTS_WITH,
    // Multiple selection
    OPERATOR_IS_ANY,
    OPERATOR_IS_NONE,
    OPERATOR_IS_ALL,
    OPERATOR_IS_NOT_ALL
  ],
  format: {},
  getValueFormatted: get_value_formatted_default_default,
  validate: {
    required: isValidRequired,
    pattern: isValidPattern,
    minLength: isValidMinLength,
    maxLength: isValidMaxLength,
    elements: isValidElements
  }
};

// node_modules/@wordpress/dataviews/build-module/field-types/color.mjs
var import_i18n14 = __toESM(require_i18n(), 1);
var import_jsx_runtime36 = __toESM(require_jsx_runtime(), 1);
function render3({ item, field }) {
  if (field.hasElements) {
    return /* @__PURE__ */ (0, import_jsx_runtime36.jsx)(RenderFromElements, { item, field });
  }
  const value = get_value_formatted_default_default({ item, field });
  if (!value || !w(value).isValid()) {
    return value;
  }
  return /* @__PURE__ */ (0, import_jsx_runtime36.jsxs)("div", { style: { display: "flex", alignItems: "center", gap: "8px" }, children: [
    /* @__PURE__ */ (0, import_jsx_runtime36.jsx)(
      "div",
      {
        style: {
          width: "16px",
          height: "16px",
          borderRadius: "50%",
          backgroundColor: value,
          border: "1px solid #ddd",
          flexShrink: 0
        }
      }
    ),
    /* @__PURE__ */ (0, import_jsx_runtime36.jsx)("span", { children: value })
  ] });
}
function isValidCustom6(item, field) {
  const value = field.getValue({ item });
  if (![void 0, "", null].includes(value) && !w(value).isValid()) {
    return (0, import_i18n14.__)("Value must be a valid color.");
  }
  return null;
}
var sort5 = (a2, b2, direction) => {
  const colorA = w(a2);
  const colorB = w(b2);
  if (!colorA.isValid() && !colorB.isValid()) {
    return 0;
  }
  if (!colorA.isValid()) {
    return direction === "asc" ? 1 : -1;
  }
  if (!colorB.isValid()) {
    return direction === "asc" ? -1 : 1;
  }
  const hslA = colorA.toHsl();
  const hslB = colorB.toHsl();
  if (hslA.h !== hslB.h) {
    return direction === "asc" ? hslA.h - hslB.h : hslB.h - hslA.h;
  }
  if (hslA.s !== hslB.s) {
    return direction === "asc" ? hslA.s - hslB.s : hslB.s - hslA.s;
  }
  return direction === "asc" ? hslA.l - hslB.l : hslB.l - hslA.l;
};
var color_default = {
  type: "color",
  render: render3,
  Edit: "color",
  sort: sort5,
  enableSorting: true,
  enableGlobalSearch: false,
  defaultOperators: [OPERATOR_IS_ANY, OPERATOR_IS_NONE],
  validOperators: [
    OPERATOR_IS,
    OPERATOR_IS_NOT,
    OPERATOR_IS_ANY,
    OPERATOR_IS_NONE
  ],
  format: {},
  getValueFormatted: get_value_formatted_default_default,
  validate: {
    required: isValidRequired,
    elements: isValidElements,
    custom: isValidCustom6
  }
};

// node_modules/@wordpress/dataviews/build-module/field-types/url.mjs
var url_default = {
  type: "url",
  render,
  Edit: "url",
  sort: sort_text_default,
  enableSorting: true,
  enableGlobalSearch: false,
  defaultOperators: [OPERATOR_IS_ANY, OPERATOR_IS_NONE],
  validOperators: [
    OPERATOR_IS,
    OPERATOR_IS_NOT,
    OPERATOR_CONTAINS,
    OPERATOR_NOT_CONTAINS,
    OPERATOR_STARTS_WITH,
    // Multiple selection
    OPERATOR_IS_ANY,
    OPERATOR_IS_NONE,
    OPERATOR_IS_ALL,
    OPERATOR_IS_NOT_ALL
  ],
  format: {},
  getValueFormatted: get_value_formatted_default_default,
  validate: {
    required: isValidRequired,
    pattern: isValidPattern,
    minLength: isValidMinLength,
    maxLength: isValidMaxLength,
    elements: isValidElements
  }
};

// node_modules/@wordpress/dataviews/build-module/field-types/no-type.mjs
var sort6 = (a2, b2, direction) => {
  if (typeof a2 === "number" && typeof b2 === "number") {
    return sort_number_default(a2, b2, direction);
  }
  return sort_text_default(a2, b2, direction);
};
var no_type_default = {
  // type: no type for this one
  render,
  Edit: null,
  sort: sort6,
  enableSorting: true,
  enableGlobalSearch: false,
  defaultOperators: [OPERATOR_IS, OPERATOR_IS_NOT],
  validOperators: getAllOperatorNames(),
  format: {},
  getValueFormatted: get_value_formatted_default_default,
  validate: {
    required: isValidRequired,
    elements: isValidElements
  }
};

// node_modules/@wordpress/dataviews/build-module/field-types/utils/get-is-valid.mjs
function getIsValid(field, fieldType) {
  let required;
  if (field.isValid?.required === true && fieldType.validate.required !== void 0) {
    required = {
      constraint: true,
      validate: fieldType.validate.required
    };
  }
  let elements;
  if ((field.isValid?.elements === true || // elements is enabled unless the field opts-out
  field.isValid?.elements === void 0 && (!!field.elements || !!field.getElements)) && fieldType.validate.elements !== void 0) {
    elements = {
      constraint: true,
      validate: fieldType.validate.elements
    };
  }
  let min;
  if (typeof field.isValid?.min === "number" && fieldType.validate.min !== void 0) {
    min = {
      constraint: field.isValid.min,
      validate: fieldType.validate.min
    };
  }
  let max;
  if (typeof field.isValid?.max === "number" && fieldType.validate.max !== void 0) {
    max = {
      constraint: field.isValid.max,
      validate: fieldType.validate.max
    };
  }
  let minLength;
  if (typeof field.isValid?.minLength === "number" && fieldType.validate.minLength !== void 0) {
    minLength = {
      constraint: field.isValid.minLength,
      validate: fieldType.validate.minLength
    };
  }
  let maxLength;
  if (typeof field.isValid?.maxLength === "number" && fieldType.validate.maxLength !== void 0) {
    maxLength = {
      constraint: field.isValid.maxLength,
      validate: fieldType.validate.maxLength
    };
  }
  let pattern;
  if (field.isValid?.pattern !== void 0 && fieldType.validate.pattern !== void 0) {
    pattern = {
      constraint: field.isValid?.pattern,
      validate: fieldType.validate.pattern
    };
  }
  const custom = field.isValid?.custom ?? fieldType.validate.custom;
  return {
    required,
    elements,
    min,
    max,
    minLength,
    maxLength,
    pattern,
    custom
  };
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/get-filter.mjs
function getFilter(fieldType) {
  return fieldType.validOperators.reduce((accumulator, operator) => {
    const operatorObj = getOperatorByName(operator);
    if (operatorObj?.filter) {
      accumulator[operator] = operatorObj.filter;
    }
    return accumulator;
  }, {});
}

// node_modules/@wordpress/dataviews/build-module/field-types/utils/get-format.mjs
function getFormat(field, fieldType) {
  return {
    ...fieldType.format,
    ...field.format
  };
}
var get_format_default = getFormat;

// node_modules/@wordpress/dataviews/build-module/field-types/index.mjs
function getFieldTypeByName(type) {
  const found = [
    email_default,
    integer_default,
    number_default,
    text_default,
    datetime_default,
    date_default,
    boolean_default,
    media_default,
    array_default,
    password_default,
    telephone_default,
    color_default,
    url_default
  ].find((fieldType) => fieldType?.type === type);
  if (!!found) {
    return found;
  }
  return no_type_default;
}
function normalizeFields(fields) {
  return fields.map((field) => {
    const fieldType = getFieldTypeByName(field.type);
    const getValue = field.getValue || get_value_from_id_default(field.id);
    const sort7 = function(a2, b2, direction) {
      const aValue = getValue({ item: a2 });
      const bValue = getValue({ item: b2 });
      return field.sort ? field.sort(aValue, bValue, direction) : fieldType.sort(aValue, bValue, direction);
    };
    return {
      id: field.id,
      label: field.label || field.id,
      header: field.header || field.label || field.id,
      description: field.description,
      placeholder: field.placeholder,
      getValue,
      setValue: field.setValue || set_value_from_id_default(field.id),
      elements: field.elements,
      getElements: field.getElements,
      hasElements: hasElements(field),
      isVisible: field.isVisible,
      enableHiding: field.enableHiding ?? true,
      readOnly: field.readOnly ?? false,
      // The type provides defaults for the following props
      type: fieldType.type,
      render: field.render ?? fieldType.render,
      Edit: getControl(field, fieldType.Edit),
      sort: sort7,
      enableSorting: field.enableSorting ?? fieldType.enableSorting,
      enableGlobalSearch: field.enableGlobalSearch ?? fieldType.enableGlobalSearch,
      isValid: getIsValid(field, fieldType),
      filterBy: get_filter_by_default(
        field,
        fieldType.defaultOperators,
        fieldType.validOperators
      ),
      filter: getFilter(fieldType),
      format: get_format_default(field, fieldType),
      getValueFormatted: field.getValueFormatted ?? fieldType.getValueFormatted
    };
  });
}

// node_modules/@wordpress/dataviews/build-module/dataform/index.mjs
var import_element32 = __toESM(require_element(), 1);

// node_modules/@wordpress/dataviews/build-module/components/dataform-context/index.mjs
var import_element21 = __toESM(require_element(), 1);
var import_jsx_runtime37 = __toESM(require_jsx_runtime(), 1);
var DataFormContext = (0, import_element21.createContext)({
  fields: []
});
DataFormContext.displayName = "DataFormContext";
function DataFormProvider({
  fields,
  children
}) {
  return /* @__PURE__ */ (0, import_jsx_runtime37.jsx)(DataFormContext.Provider, { value: { fields }, children });
}
var dataform_context_default = DataFormContext;

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/data-form-layout.mjs
var import_element31 = __toESM(require_element(), 1);

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/regular/index.mjs
var import_element22 = __toESM(require_element(), 1);
var import_components19 = __toESM(require_components(), 1);

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/normalize-form.mjs
var DEFAULT_LAYOUT = {
  type: "regular",
  labelPosition: "top"
};
var normalizeCardSummaryField = (sum) => {
  if (typeof sum === "string") {
    return [{ id: sum, visibility: "when-collapsed" }];
  }
  return sum.map((item) => {
    if (typeof item === "string") {
      return { id: item, visibility: "when-collapsed" };
    }
    return { id: item.id, visibility: item.visibility };
  });
};
function normalizeLayout(layout) {
  let normalizedLayout = DEFAULT_LAYOUT;
  if (layout?.type === "regular") {
    normalizedLayout = {
      type: "regular",
      labelPosition: layout?.labelPosition ?? "top"
    };
  } else if (layout?.type === "panel") {
    const summary = layout.summary ?? [];
    const normalizedSummary = Array.isArray(summary) ? summary : [summary];
    normalizedLayout = {
      type: "panel",
      labelPosition: layout?.labelPosition ?? "side",
      openAs: layout?.openAs ?? "dropdown",
      summary: normalizedSummary,
      editVisibility: layout?.editVisibility ?? "on-hover"
    };
  } else if (layout?.type === "card") {
    if (layout.withHeader === false) {
      normalizedLayout = {
        type: "card",
        withHeader: false,
        isOpened: true,
        summary: [],
        isCollapsible: false
      };
    } else {
      const summary = layout.summary ?? [];
      normalizedLayout = {
        type: "card",
        withHeader: true,
        isOpened: typeof layout.isOpened === "boolean" ? layout.isOpened : true,
        summary: normalizeCardSummaryField(summary),
        isCollapsible: layout.isCollapsible === void 0 ? true : layout.isCollapsible
      };
    }
  } else if (layout?.type === "row") {
    normalizedLayout = {
      type: "row",
      alignment: layout?.alignment ?? "center",
      styles: layout?.styles ?? {}
    };
  } else if (layout?.type === "details") {
    normalizedLayout = {
      type: "details",
      summary: layout?.summary ?? ""
    };
  }
  return normalizedLayout;
}
function normalizeForm(form) {
  const normalizedFormLayout = normalizeLayout(form?.layout);
  const normalizedFields = (form.fields ?? []).map(
    (field) => {
      if (typeof field === "string") {
        return {
          id: field,
          layout: normalizedFormLayout
        };
      }
      const fieldLayout = field.layout ? normalizeLayout(field.layout) : normalizedFormLayout;
      return {
        id: field.id,
        layout: fieldLayout,
        ...!!field.label && { label: field.label },
        ...!!field.description && {
          description: field.description
        },
        ..."children" in field && Array.isArray(field.children) && {
          children: normalizeForm({
            fields: field.children,
            layout: DEFAULT_LAYOUT
          }).fields
        }
      };
    }
  );
  return {
    layout: normalizedFormLayout,
    fields: normalizedFields
  };
}
var normalize_form_default = normalizeForm;

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/regular/index.mjs
var import_jsx_runtime38 = __toESM(require_jsx_runtime(), 1);
function Header({ title }) {
  return /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(
    Stack,
    {
      direction: "column",
      className: "dataforms-layouts-regular__header",
      gap: "lg",
      children: /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(Stack, { direction: "row", align: "center", children: /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(import_components19.__experimentalHeading, { level: 2, size: 13, children: title }) })
    }
  );
}
function FormRegularField({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const { fields } = (0, import_element22.useContext)(dataform_context_default);
  const layout = field.layout;
  const form = (0, import_element22.useMemo)(
    () => ({
      layout: DEFAULT_LAYOUT,
      fields: !!field.children ? field.children : []
    }),
    [field]
  );
  if (!!field.children) {
    return /* @__PURE__ */ (0, import_jsx_runtime38.jsxs)(import_jsx_runtime38.Fragment, { children: [
      !hideLabelFromVision && field.label && /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(Header, { title: field.label }),
      /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(
        DataFormLayout,
        {
          data,
          form,
          onChange,
          validity: validity?.children
        }
      )
    ] });
  }
  const labelPosition = layout.labelPosition;
  const fieldDefinition = fields.find(
    (fieldDef) => fieldDef.id === field.id
  );
  if (!fieldDefinition || !fieldDefinition.Edit) {
    return null;
  }
  if (labelPosition === "side") {
    return /* @__PURE__ */ (0, import_jsx_runtime38.jsxs)(
      Stack,
      {
        direction: "row",
        className: "dataforms-layouts-regular__field",
        gap: "sm",
        children: [
          /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(
            "div",
            {
              className: clsx_default(
                "dataforms-layouts-regular__field-label",
                `dataforms-layouts-regular__field-label--label-position-${labelPosition}`
              ),
              children: /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(import_components19.BaseControl.VisualLabel, { children: fieldDefinition.label })
            }
          ),
          /* @__PURE__ */ (0, import_jsx_runtime38.jsx)("div", { className: "dataforms-layouts-regular__field-control", children: fieldDefinition.readOnly === true ? /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(
            fieldDefinition.render,
            {
              item: data,
              field: fieldDefinition
            }
          ) : /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(
            fieldDefinition.Edit,
            {
              data,
              field: fieldDefinition,
              onChange,
              hideLabelFromVision: true,
              markWhenOptional,
              validity
            },
            fieldDefinition.id
          ) })
        ]
      }
    );
  }
  return /* @__PURE__ */ (0, import_jsx_runtime38.jsx)("div", { className: "dataforms-layouts-regular__field", children: fieldDefinition.readOnly === true ? /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(import_jsx_runtime38.Fragment, { children: /* @__PURE__ */ (0, import_jsx_runtime38.jsxs)(import_jsx_runtime38.Fragment, { children: [
    !hideLabelFromVision && labelPosition !== "none" && /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(import_components19.BaseControl.VisualLabel, { children: fieldDefinition.label }),
    /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(
      fieldDefinition.render,
      {
        item: data,
        field: fieldDefinition
      }
    )
  ] }) }) : /* @__PURE__ */ (0, import_jsx_runtime38.jsx)(
    fieldDefinition.Edit,
    {
      data,
      field: fieldDefinition,
      onChange,
      hideLabelFromVision: labelPosition === "none" ? true : hideLabelFromVision,
      markWhenOptional,
      validity
    }
  ) });
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/panel/modal.mjs
var import_deepmerge2 = __toESM(require_cjs(), 1);
var import_components22 = __toESM(require_components(), 1);
var import_i18n17 = __toESM(require_i18n(), 1);
var import_element27 = __toESM(require_element(), 1);
var import_compose2 = __toESM(require_compose(), 1);

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/panel/summary-button.mjs
var import_components21 = __toESM(require_components(), 1);
var import_i18n15 = __toESM(require_i18n(), 1);
var import_compose = __toESM(require_compose(), 1);
var import_element23 = __toESM(require_element(), 1);

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/panel/utils/get-label-classname.mjs
function getLabelClassName(labelPosition, showError) {
  return clsx_default(
    "dataforms-layouts-panel__field-label",
    `dataforms-layouts-panel__field-label--label-position-${labelPosition}`,
    { "has-error": showError }
  );
}
var get_label_classname_default = getLabelClassName;

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/panel/utils/get-label-content.mjs
var import_components20 = __toESM(require_components(), 1);
var import_jsx_runtime39 = __toESM(require_jsx_runtime(), 1);
function getLabelContent(showError, errorMessage, fieldLabel) {
  return showError ? /* @__PURE__ */ (0, import_jsx_runtime39.jsx)(import_components20.Tooltip, { text: errorMessage, placement: "top", children: /* @__PURE__ */ (0, import_jsx_runtime39.jsxs)("span", { className: "dataforms-layouts-panel__field-label-error-content", children: [
    /* @__PURE__ */ (0, import_jsx_runtime39.jsx)(import_components20.Icon, { icon: error_default, size: 16 }),
    fieldLabel
  ] }) }) : fieldLabel;
}
var get_label_content_default = getLabelContent;

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/panel/utils/get-first-validation-error.mjs
function getFirstValidationError(validity) {
  if (!validity) {
    return void 0;
  }
  const validityRules = Object.keys(validity).filter(
    (key) => key !== "children"
  );
  for (const key of validityRules) {
    const rule = validity[key];
    if (rule === void 0) {
      continue;
    }
    if (rule.type === "invalid") {
      if (rule.message) {
        return rule.message;
      }
      if (key === "required") {
        return "A required field is empty";
      }
      return "Unidentified validation error";
    }
  }
  if (validity.children) {
    for (const childValidity of Object.values(validity.children)) {
      const childError = getFirstValidationError(childValidity);
      if (childError) {
        return childError;
      }
    }
  }
  return void 0;
}
var get_first_validation_error_default = getFirstValidationError;

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/panel/summary-button.mjs
var import_jsx_runtime40 = __toESM(require_jsx_runtime(), 1);
function SummaryButton({
  data,
  field,
  fieldLabel,
  summaryFields,
  validity,
  touched,
  disabled,
  onClick,
  "aria-expanded": ariaExpanded
}) {
  const { labelPosition, editVisibility } = field.layout;
  const errorMessage = get_first_validation_error_default(validity);
  const showError = touched && !!errorMessage;
  const labelClassName = get_label_classname_default(labelPosition, showError);
  const labelContent = get_label_content_default(showError, errorMessage, fieldLabel);
  const className = clsx_default(
    "dataforms-layouts-panel__field-trigger",
    `dataforms-layouts-panel__field-trigger--label-${labelPosition}`,
    {
      "is-disabled": disabled,
      "dataforms-layouts-panel__field-trigger--edit-always": editVisibility === "always"
    }
  );
  const controlId = (0, import_compose.useInstanceId)(
    SummaryButton,
    "dataforms-layouts-panel__field-control"
  );
  const ariaLabel = showError ? (0, import_i18n15.sprintf)(
    // translators: %s: Field name.
    (0, import_i18n15._x)("Edit %s (has errors)", "field"),
    fieldLabel || ""
  ) : (0, import_i18n15.sprintf)(
    // translators: %s: Field name.
    (0, import_i18n15._x)("Edit %s", "field"),
    fieldLabel || ""
  );
  const rowRef = (0, import_element23.useRef)(null);
  const handleRowClick = () => {
    const selection = rowRef.current?.ownerDocument.defaultView?.getSelection();
    if (selection && selection.toString().length > 0) {
      return;
    }
    onClick();
  };
  const handleKeyDown = (event) => {
    if (event.target === event.currentTarget && (event.key === "Enter" || event.key === " ")) {
      event.preventDefault();
      onClick();
    }
  };
  return /* @__PURE__ */ (0, import_jsx_runtime40.jsxs)(
    "div",
    {
      ref: rowRef,
      className,
      onClick: !disabled ? handleRowClick : void 0,
      onKeyDown: !disabled ? handleKeyDown : void 0,
      children: [
        labelPosition !== "none" && /* @__PURE__ */ (0, import_jsx_runtime40.jsx)("span", { className: labelClassName, children: labelContent }),
        labelPosition === "none" && showError && /* @__PURE__ */ (0, import_jsx_runtime40.jsx)(import_components21.Tooltip, { text: errorMessage, placement: "top", children: /* @__PURE__ */ (0, import_jsx_runtime40.jsx)("span", { className: "dataforms-layouts-panel__field-label-error-content", children: /* @__PURE__ */ (0, import_jsx_runtime40.jsx)(import_components21.Icon, { icon: error_default, size: 16 }) }) }),
        /* @__PURE__ */ (0, import_jsx_runtime40.jsx)(
          "span",
          {
            id: `${controlId}`,
            className: "dataforms-layouts-panel__field-control",
            children: summaryFields.length > 1 ? /* @__PURE__ */ (0, import_jsx_runtime40.jsx)(
              "span",
              {
                style: {
                  display: "flex",
                  flexDirection: "column",
                  alignItems: "flex-start",
                  width: "100%",
                  gap: "2px"
                },
                children: summaryFields.map((summaryField) => /* @__PURE__ */ (0, import_jsx_runtime40.jsx)(
                  "span",
                  {
                    style: { width: "100%" },
                    children: /* @__PURE__ */ (0, import_jsx_runtime40.jsx)(
                      summaryField.render,
                      {
                        item: data,
                        field: summaryField
                      }
                    )
                  },
                  summaryField.id
                ))
              }
            ) : summaryFields.map((summaryField) => /* @__PURE__ */ (0, import_jsx_runtime40.jsx)(
              summaryField.render,
              {
                item: data,
                field: summaryField
              },
              summaryField.id
            ))
          }
        ),
        !disabled && /* @__PURE__ */ (0, import_jsx_runtime40.jsx)(
          import_components21.Button,
          {
            className: "dataforms-layouts-panel__field-trigger-icon",
            label: ariaLabel,
            showTooltip: false,
            icon: pencil_default,
            size: "small",
            "aria-expanded": ariaExpanded,
            "aria-haspopup": "dialog",
            "aria-describedby": `${controlId}`
          }
        )
      ]
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/hooks/use-form-validity.mjs
var import_deepmerge = __toESM(require_cjs(), 1);
var import_es6 = __toESM(require_es6(), 1);
var import_element24 = __toESM(require_element(), 1);
var import_i18n16 = __toESM(require_i18n(), 1);
function isFormValid(formValidity) {
  if (!formValidity) {
    return true;
  }
  return Object.values(formValidity).every((fieldValidation) => {
    return Object.entries(fieldValidation).every(
      ([key, validation]) => {
        if (key === "children" && validation && typeof validation === "object") {
          return isFormValid(validation);
        }
        return validation.type !== "invalid" && validation.type !== "validating";
      }
    );
  });
}
function getFormFieldsToValidate(form, fields) {
  const normalizedForm = normalize_form_default(form);
  if (normalizedForm.fields.length === 0) {
    return [];
  }
  const fieldsMap = /* @__PURE__ */ new Map();
  fields.forEach((field) => {
    fieldsMap.set(field.id, field);
  });
  function processFormField(formField) {
    if ("children" in formField && Array.isArray(formField.children)) {
      const processedChildren = formField.children.map(processFormField).filter((child) => child !== null);
      if (processedChildren.length === 0) {
        return null;
      }
      const fieldDef2 = fieldsMap.get(formField.id);
      if (fieldDef2) {
        const [normalizedField2] = normalizeFields([
          fieldDef2
        ]);
        return {
          id: formField.id,
          children: processedChildren,
          field: normalizedField2
        };
      }
      return {
        id: formField.id,
        children: processedChildren
      };
    }
    const fieldDef = fieldsMap.get(formField.id);
    if (!fieldDef) {
      return null;
    }
    const [normalizedField] = normalizeFields([fieldDef]);
    return {
      id: formField.id,
      children: [],
      field: normalizedField
    };
  }
  const toValidate = normalizedForm.fields.map(processFormField).filter((field) => field !== null);
  return toValidate;
}
function setValidityAtPath(formValidity, fieldValidity, path) {
  if (!formValidity) {
    formValidity = {};
  }
  if (path.length === 0) {
    return formValidity;
  }
  const result = { ...formValidity };
  let current = result;
  for (let i2 = 0; i2 < path.length - 1; i2++) {
    const segment = path[i2];
    if (!current[segment]) {
      current[segment] = {};
    }
    current[segment] = { ...current[segment] };
    current = current[segment];
  }
  const finalKey = path[path.length - 1];
  current[finalKey] = {
    ...current[finalKey] || {},
    ...fieldValidity
  };
  return result;
}
function removeValidationProperty(formValidity, path, property) {
  if (!formValidity || path.length === 0) {
    return formValidity;
  }
  const result = { ...formValidity };
  let current = result;
  for (let i2 = 0; i2 < path.length - 1; i2++) {
    const segment = path[i2];
    if (!current[segment]) {
      return formValidity;
    }
    current[segment] = { ...current[segment] };
    current = current[segment];
  }
  const finalKey = path[path.length - 1];
  if (!current[finalKey]) {
    return formValidity;
  }
  const fieldValidity = { ...current[finalKey] };
  delete fieldValidity[property];
  if (Object.keys(fieldValidity).length === 0) {
    delete current[finalKey];
  } else {
    current[finalKey] = fieldValidity;
  }
  if (Object.keys(result).length === 0) {
    return void 0;
  }
  return result;
}
function handleElementsValidationAsync(promise, formField, promiseHandler) {
  const { elementsCounterRef, setFormValidity, path, item } = promiseHandler;
  const currentToken = (elementsCounterRef.current[formField.id] || 0) + 1;
  elementsCounterRef.current[formField.id] = currentToken;
  promise.then((result) => {
    if (currentToken !== elementsCounterRef.current[formField.id]) {
      return;
    }
    if (!Array.isArray(result)) {
      setFormValidity((prev) => {
        const newFormValidity = setValidityAtPath(
          prev,
          {
            elements: {
              type: "invalid",
              message: (0, import_i18n16.__)("Could not validate elements.")
            }
          },
          [...path, formField.id]
        );
        return newFormValidity;
      });
      return;
    }
    if (formField.field?.isValid.elements && !formField.field.isValid.elements.validate(item, {
      ...formField.field,
      elements: result
    })) {
      setFormValidity((prev) => {
        const newFormValidity = setValidityAtPath(
          prev,
          {
            elements: {
              type: "invalid",
              message: (0, import_i18n16.__)(
                "Value must be one of the elements."
              )
            }
          },
          [...path, formField.id]
        );
        return newFormValidity;
      });
    } else {
      setFormValidity((prev) => {
        return removeValidationProperty(
          prev,
          [...path, formField.id],
          "elements"
        );
      });
    }
  }).catch((error) => {
    if (currentToken !== elementsCounterRef.current[formField.id]) {
      return;
    }
    let errorMessage;
    if (error instanceof Error) {
      errorMessage = error.message;
    } else {
      errorMessage = String(error) || (0, import_i18n16.__)(
        "Unknown error when running elements validation asynchronously."
      );
    }
    setFormValidity((prev) => {
      const newFormValidity = setValidityAtPath(
        prev,
        {
          elements: {
            type: "invalid",
            message: errorMessage
          }
        },
        [...path, formField.id]
      );
      return newFormValidity;
    });
  });
}
function handleCustomValidationAsync(promise, formField, promiseHandler) {
  const { customCounterRef, setFormValidity, path } = promiseHandler;
  const currentToken = (customCounterRef.current[formField.id] || 0) + 1;
  customCounterRef.current[formField.id] = currentToken;
  promise.then((result) => {
    if (currentToken !== customCounterRef.current[formField.id]) {
      return;
    }
    if (result === null) {
      setFormValidity((prev) => {
        return removeValidationProperty(
          prev,
          [...path, formField.id],
          "custom"
        );
      });
      return;
    }
    if (typeof result === "string") {
      setFormValidity((prev) => {
        const newFormValidity = setValidityAtPath(
          prev,
          {
            custom: {
              type: "invalid",
              message: result
            }
          },
          [...path, formField.id]
        );
        return newFormValidity;
      });
      return;
    }
    setFormValidity((prev) => {
      const newFormValidity = setValidityAtPath(
        prev,
        {
          custom: {
            type: "invalid",
            message: (0, import_i18n16.__)("Validation could not be processed.")
          }
        },
        [...path, formField.id]
      );
      return newFormValidity;
    });
  }).catch((error) => {
    if (currentToken !== customCounterRef.current[formField.id]) {
      return;
    }
    let errorMessage;
    if (error instanceof Error) {
      errorMessage = error.message;
    } else {
      errorMessage = String(error) || (0, import_i18n16.__)(
        "Unknown error when running custom validation asynchronously."
      );
    }
    setFormValidity((prev) => {
      const newFormValidity = setValidityAtPath(
        prev,
        {
          custom: {
            type: "invalid",
            message: errorMessage
          }
        },
        [...path, formField.id]
      );
      return newFormValidity;
    });
  });
}
function validateFormField(item, formField, promiseHandler) {
  if (formField.field?.isValid.required && !formField.field.isValid.required.validate(item, formField.field)) {
    return {
      required: { type: "invalid" }
    };
  }
  if (formField.field?.isValid.pattern && !formField.field.isValid.pattern.validate(item, formField.field)) {
    return {
      pattern: {
        type: "invalid",
        message: (0, import_i18n16.__)("Value does not match the required pattern.")
      }
    };
  }
  if (formField.field?.isValid.min && !formField.field.isValid.min.validate(item, formField.field)) {
    return {
      min: {
        type: "invalid",
        message: (0, import_i18n16.__)("Value is below the minimum.")
      }
    };
  }
  if (formField.field?.isValid.max && !formField.field.isValid.max.validate(item, formField.field)) {
    return {
      max: {
        type: "invalid",
        message: (0, import_i18n16.__)("Value is above the maximum.")
      }
    };
  }
  if (formField.field?.isValid.minLength && !formField.field.isValid.minLength.validate(item, formField.field)) {
    return {
      minLength: {
        type: "invalid",
        message: (0, import_i18n16.__)("Value is too short.")
      }
    };
  }
  if (formField.field?.isValid.maxLength && !formField.field.isValid.maxLength.validate(item, formField.field)) {
    return {
      maxLength: {
        type: "invalid",
        message: (0, import_i18n16.__)("Value is too long.")
      }
    };
  }
  if (formField.field?.isValid.elements && formField.field.hasElements && !formField.field.getElements && Array.isArray(formField.field.elements) && !formField.field.isValid.elements.validate(item, formField.field)) {
    return {
      elements: {
        type: "invalid",
        message: (0, import_i18n16.__)("Value must be one of the elements.")
      }
    };
  }
  let customError;
  if (!!formField.field && formField.field.isValid.custom) {
    try {
      const value = formField.field.getValue({ item });
      customError = formField.field.isValid.custom(
        (0, import_deepmerge.default)(
          item,
          formField.field.setValue({
            item,
            value
          })
        ),
        formField.field
      );
    } catch (error) {
      let errorMessage;
      if (error instanceof Error) {
        errorMessage = error.message;
      } else {
        errorMessage = String(error) || (0, import_i18n16.__)("Unknown error when running custom validation.");
      }
      return {
        custom: {
          type: "invalid",
          message: errorMessage
        }
      };
    }
  }
  if (typeof customError === "string") {
    return {
      custom: {
        type: "invalid",
        message: customError
      }
    };
  }
  const fieldValidity = {};
  if (!!formField.field && formField.field.isValid.elements && formField.field.hasElements && typeof formField.field.getElements === "function") {
    handleElementsValidationAsync(
      formField.field.getElements(),
      formField,
      promiseHandler
    );
    fieldValidity.elements = {
      type: "validating",
      message: (0, import_i18n16.__)("Validating\u2026")
    };
  }
  if (customError instanceof Promise) {
    handleCustomValidationAsync(customError, formField, promiseHandler);
    fieldValidity.custom = {
      type: "validating",
      message: (0, import_i18n16.__)("Validating\u2026")
    };
  }
  if (Object.keys(fieldValidity).length > 0) {
    return fieldValidity;
  }
  if (formField.children.length > 0) {
    const result = {};
    formField.children.forEach((child) => {
      result[child.id] = validateFormField(item, child, {
        ...promiseHandler,
        path: [...promiseHandler.path, formField.id, "children"]
      });
    });
    const filteredResult = {};
    Object.entries(result).forEach(([key, value]) => {
      if (value !== void 0) {
        filteredResult[key] = value;
      }
    });
    if (Object.keys(filteredResult).length === 0) {
      return void 0;
    }
    return {
      children: filteredResult
    };
  }
  return void 0;
}
function getFormFieldValue(formField, item) {
  const fieldValue = formField?.field?.getValue({ item });
  if (formField.children.length === 0) {
    return fieldValue;
  }
  const childrenValues = formField.children.map(
    (child) => getFormFieldValue(child, item)
  );
  if (!childrenValues) {
    return fieldValue;
  }
  return {
    value: fieldValue,
    children: childrenValues
  };
}
function useFormValidity(item, fields, form) {
  const [formValidity, setFormValidity] = (0, import_element24.useState)();
  const customCounterRef = (0, import_element24.useRef)({});
  const elementsCounterRef = (0, import_element24.useRef)({});
  const previousValuesRef = (0, import_element24.useRef)({});
  const validate = (0, import_element24.useCallback)(() => {
    const promiseHandler = {
      customCounterRef,
      elementsCounterRef,
      setFormValidity,
      path: [],
      item
    };
    const formFieldsToValidate = getFormFieldsToValidate(form, fields);
    if (formFieldsToValidate.length === 0) {
      setFormValidity(void 0);
      return;
    }
    const newFormValidity = {};
    const untouchedFields = [];
    formFieldsToValidate.forEach((formField) => {
      const value = getFormFieldValue(formField, item);
      if (previousValuesRef.current.hasOwnProperty(formField.id) && (0, import_es6.default)(
        previousValuesRef.current[formField.id],
        value
      )) {
        untouchedFields.push(formField.id);
        return;
      }
      previousValuesRef.current[formField.id] = value;
      const fieldValidity = validateFormField(
        item,
        formField,
        promiseHandler
      );
      if (fieldValidity !== void 0) {
        newFormValidity[formField.id] = fieldValidity;
      }
    });
    setFormValidity((existingFormValidity) => {
      let validity = {
        ...existingFormValidity,
        ...newFormValidity
      };
      const fieldsToKeep = [
        ...untouchedFields,
        ...Object.keys(newFormValidity)
      ];
      Object.keys(validity).forEach((key) => {
        if (validity && !fieldsToKeep.includes(key)) {
          delete validity[key];
        }
      });
      if (Object.keys(validity).length === 0) {
        validity = void 0;
      }
      const areEqual = (0, import_es6.default)(existingFormValidity, validity);
      if (areEqual) {
        return existingFormValidity;
      }
      return validity;
    });
  }, [item, fields, form]);
  (0, import_element24.useEffect)(() => {
    validate();
  }, [validate]);
  return {
    validity: formValidity,
    isValid: isFormValid(formValidity)
  };
}
var use_form_validity_default = useFormValidity;

// node_modules/@wordpress/dataviews/build-module/hooks/use-report-validity.mjs
var import_element25 = __toESM(require_element(), 1);
function useReportValidity(ref, shouldReport) {
  (0, import_element25.useEffect)(() => {
    if (shouldReport && ref.current) {
      const inputs = ref.current.querySelectorAll(
        "input, textarea, select"
      );
      inputs.forEach((input) => {
        input.reportValidity();
      });
    }
  }, [shouldReport, ref]);
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/panel/utils/use-field-from-form-field.mjs
var import_element26 = __toESM(require_element(), 1);

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/get-summary-fields.mjs
function extractSummaryIds(summary) {
  if (Array.isArray(summary)) {
    return summary.map(
      (item) => typeof item === "string" ? item : item.id
    );
  }
  return [];
}
var getSummaryFields = (summaryField, fields) => {
  if (Array.isArray(summaryField) && summaryField.length > 0) {
    const summaryIds = extractSummaryIds(summaryField);
    return summaryIds.map(
      (summaryId) => fields.find((_field) => _field.id === summaryId)
    ).filter((_field) => _field !== void 0);
  }
  return [];
};

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/panel/utils/use-field-from-form-field.mjs
var getFieldDefinition = (field, fields) => {
  const fieldDefinition = fields.find((_field) => _field.id === field.id);
  if (!fieldDefinition) {
    return fields.find((_field) => {
      if (!!field.children) {
        const simpleChildren = field.children.filter(
          (child) => !child.children
        );
        if (simpleChildren.length === 0) {
          return false;
        }
        return _field.id === simpleChildren[0].id;
      }
      return _field.id === field.id;
    });
  }
  return fieldDefinition;
};
function useFieldFromFormField(field) {
  const { fields } = (0, import_element26.useContext)(dataform_context_default);
  const layout = field.layout;
  const summaryFields = getSummaryFields(layout.summary, fields);
  const fieldDefinition = getFieldDefinition(field, fields);
  const fieldLabel = !!field.children ? field.label : fieldDefinition?.label;
  if (summaryFields.length === 0) {
    return {
      summaryFields: fieldDefinition ? [fieldDefinition] : [],
      fieldDefinition,
      fieldLabel
    };
  }
  return {
    summaryFields,
    fieldDefinition,
    fieldLabel
  };
}
var use_field_from_form_field_default = useFieldFromFormField;

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/panel/modal.mjs
var import_jsx_runtime41 = __toESM(require_jsx_runtime(), 1);
function ModalContent({
  data,
  field,
  onChange,
  fieldLabel,
  onClose,
  touched
}) {
  const { fields } = (0, import_element27.useContext)(dataform_context_default);
  const [changes, setChanges] = (0, import_element27.useState)({});
  const modalData = (0, import_element27.useMemo)(() => {
    return (0, import_deepmerge2.default)(data, changes, {
      arrayMerge: (target, source) => source
    });
  }, [data, changes]);
  const form = (0, import_element27.useMemo)(
    () => ({
      layout: DEFAULT_LAYOUT,
      fields: !!field.children ? field.children : (
        // If not explicit children return the field id itself.
        [{ id: field.id, layout: DEFAULT_LAYOUT }]
      )
    }),
    [field]
  );
  const fieldsAsFieldType = fields.map((f2) => ({
    ...f2,
    Edit: f2.Edit === null ? void 0 : f2.Edit,
    isValid: {
      required: f2.isValid.required?.constraint,
      elements: f2.isValid.elements?.constraint,
      min: f2.isValid.min?.constraint,
      max: f2.isValid.max?.constraint,
      pattern: f2.isValid.pattern?.constraint,
      minLength: f2.isValid.minLength?.constraint,
      maxLength: f2.isValid.maxLength?.constraint
    }
  }));
  const { validity } = use_form_validity_default(modalData, fieldsAsFieldType, form);
  const onApply = () => {
    onChange(changes);
    onClose();
  };
  const handleOnChange = (newValue) => {
    setChanges(
      (prev) => (0, import_deepmerge2.default)(prev, newValue, {
        arrayMerge: (target, source) => source
      })
    );
  };
  const focusOnMountRef = (0, import_compose2.useFocusOnMount)("firstInputElement");
  const contentRef = (0, import_element27.useRef)(null);
  const mergedRef = (0, import_compose2.useMergeRefs)([focusOnMountRef, contentRef]);
  useReportValidity(contentRef, touched);
  return /* @__PURE__ */ (0, import_jsx_runtime41.jsxs)(
    import_components22.Modal,
    {
      className: "dataforms-layouts-panel__modal",
      onRequestClose: onClose,
      isFullScreen: false,
      title: fieldLabel,
      size: "medium",
      children: [
        /* @__PURE__ */ (0, import_jsx_runtime41.jsx)("div", { ref: mergedRef, children: /* @__PURE__ */ (0, import_jsx_runtime41.jsx)(
          DataFormLayout,
          {
            data: modalData,
            form,
            onChange: handleOnChange,
            validity,
            children: (FieldLayout, childField, childFieldValidity, markWhenOptional) => /* @__PURE__ */ (0, import_jsx_runtime41.jsx)(
              FieldLayout,
              {
                data: modalData,
                field: childField,
                onChange: handleOnChange,
                hideLabelFromVision: form.fields.length < 2,
                markWhenOptional,
                validity: childFieldValidity
              },
              childField.id
            )
          }
        ) }),
        /* @__PURE__ */ (0, import_jsx_runtime41.jsxs)(
          Stack,
          {
            direction: "row",
            className: "dataforms-layouts-panel__modal-footer",
            gap: "md",
            children: [
              /* @__PURE__ */ (0, import_jsx_runtime41.jsx)(import_components22.__experimentalSpacer, { style: { flex: 1 } }),
              /* @__PURE__ */ (0, import_jsx_runtime41.jsx)(
                import_components22.Button,
                {
                  variant: "tertiary",
                  onClick: onClose,
                  __next40pxDefaultSize: true,
                  children: (0, import_i18n17.__)("Cancel")
                }
              ),
              /* @__PURE__ */ (0, import_jsx_runtime41.jsx)(
                import_components22.Button,
                {
                  variant: "primary",
                  onClick: onApply,
                  __next40pxDefaultSize: true,
                  children: (0, import_i18n17.__)("Apply")
                }
              )
            ]
          }
        )
      ]
    }
  );
}
function PanelModal({
  data,
  field,
  onChange,
  validity
}) {
  const [touched, setTouched] = (0, import_element27.useState)(false);
  const [isOpen, setIsOpen] = (0, import_element27.useState)(false);
  const { fieldDefinition, fieldLabel, summaryFields } = use_field_from_form_field_default(field);
  if (!fieldDefinition) {
    return null;
  }
  const handleClose = () => {
    setIsOpen(false);
    setTouched(true);
  };
  return /* @__PURE__ */ (0, import_jsx_runtime41.jsxs)(import_jsx_runtime41.Fragment, { children: [
    /* @__PURE__ */ (0, import_jsx_runtime41.jsx)(
      SummaryButton,
      {
        data,
        field,
        fieldLabel,
        summaryFields,
        validity,
        touched,
        disabled: fieldDefinition.readOnly === true,
        onClick: () => setIsOpen(true),
        "aria-expanded": isOpen
      }
    ),
    isOpen && /* @__PURE__ */ (0, import_jsx_runtime41.jsx)(
      ModalContent,
      {
        data,
        field,
        onChange,
        fieldLabel: fieldLabel ?? "",
        onClose: handleClose,
        touched
      }
    )
  ] });
}
var modal_default = PanelModal;

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/panel/dropdown.mjs
var import_components23 = __toESM(require_components(), 1);
var import_i18n18 = __toESM(require_i18n(), 1);
var import_element28 = __toESM(require_element(), 1);
var import_compose3 = __toESM(require_compose(), 1);
var import_jsx_runtime42 = __toESM(require_jsx_runtime(), 1);
function DropdownHeader({
  title,
  onClose
}) {
  return /* @__PURE__ */ (0, import_jsx_runtime42.jsx)(
    Stack,
    {
      direction: "column",
      className: "dataforms-layouts-panel__dropdown-header",
      gap: "lg",
      children: /* @__PURE__ */ (0, import_jsx_runtime42.jsxs)(Stack, { direction: "row", gap: "sm", align: "center", children: [
        title && /* @__PURE__ */ (0, import_jsx_runtime42.jsx)(import_components23.__experimentalHeading, { level: 2, size: 13, children: title }),
        /* @__PURE__ */ (0, import_jsx_runtime42.jsx)(import_components23.__experimentalSpacer, { style: { flex: 1 } }),
        onClose && /* @__PURE__ */ (0, import_jsx_runtime42.jsx)(
          import_components23.Button,
          {
            label: (0, import_i18n18.__)("Close"),
            icon: close_small_default,
            onClick: onClose,
            size: "small"
          }
        )
      ] })
    }
  );
}
function DropdownContentWithValidation({
  touched,
  children
}) {
  const ref = (0, import_element28.useRef)(null);
  useReportValidity(ref, touched);
  return /* @__PURE__ */ (0, import_jsx_runtime42.jsx)("div", { ref, children });
}
function PanelDropdown({
  data,
  field,
  onChange,
  validity
}) {
  const [touched, setTouched] = (0, import_element28.useState)(false);
  const [popoverAnchor, setPopoverAnchor] = (0, import_element28.useState)(
    null
  );
  const popoverProps = (0, import_element28.useMemo)(
    () => ({
      // Anchor the popover to the middle of the entire row so that it doesn't
      // move around when the label changes.
      anchor: popoverAnchor,
      placement: "left-start",
      offset: 36,
      shift: true
    }),
    [popoverAnchor]
  );
  const [dialogRef, dialogProps] = (0, import_compose3.__experimentalUseDialog)({
    focusOnMount: "firstInputElement"
  });
  const form = (0, import_element28.useMemo)(
    () => ({
      layout: DEFAULT_LAYOUT,
      fields: !!field.children ? field.children : (
        // If not explicit children return the field id itself.
        [{ id: field.id, layout: DEFAULT_LAYOUT }]
      )
    }),
    [field]
  );
  const formValidity = (0, import_element28.useMemo)(() => {
    if (validity === void 0) {
      return void 0;
    }
    if (!!field.children) {
      return validity?.children;
    }
    return { [field.id]: validity };
  }, [validity, field]);
  const { fieldDefinition, fieldLabel, summaryFields } = use_field_from_form_field_default(field);
  if (!fieldDefinition) {
    return null;
  }
  return /* @__PURE__ */ (0, import_jsx_runtime42.jsx)(
    "div",
    {
      ref: setPopoverAnchor,
      className: "dataforms-layouts-panel__field-dropdown-anchor",
      children: /* @__PURE__ */ (0, import_jsx_runtime42.jsx)(
        import_components23.Dropdown,
        {
          contentClassName: "dataforms-layouts-panel__field-dropdown",
          popoverProps,
          focusOnMount: false,
          onToggle: (willOpen) => {
            if (!willOpen) {
              setTouched(true);
            }
          },
          renderToggle: ({ isOpen, onToggle }) => /* @__PURE__ */ (0, import_jsx_runtime42.jsx)(
            SummaryButton,
            {
              data,
              field,
              fieldLabel,
              summaryFields,
              validity,
              touched,
              disabled: fieldDefinition.readOnly === true,
              onClick: onToggle,
              "aria-expanded": isOpen
            }
          ),
          renderContent: ({ onClose }) => /* @__PURE__ */ (0, import_jsx_runtime42.jsx)(DropdownContentWithValidation, { touched, children: /* @__PURE__ */ (0, import_jsx_runtime42.jsxs)("div", { ref: dialogRef, ...dialogProps, children: [
            /* @__PURE__ */ (0, import_jsx_runtime42.jsx)(
              DropdownHeader,
              {
                title: fieldLabel,
                onClose
              }
            ),
            /* @__PURE__ */ (0, import_jsx_runtime42.jsx)(
              DataFormLayout,
              {
                data,
                form,
                onChange,
                validity: formValidity,
                children: (FieldLayout, childField, childFieldValidity, markWhenOptional) => /* @__PURE__ */ (0, import_jsx_runtime42.jsx)(
                  FieldLayout,
                  {
                    data,
                    field: childField,
                    onChange,
                    hideLabelFromVision: (form?.fields ?? []).length < 2,
                    markWhenOptional,
                    validity: childFieldValidity
                  },
                  childField.id
                )
              }
            )
          ] }) })
        }
      )
    }
  );
}
var dropdown_default = PanelDropdown;

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/panel/index.mjs
var import_jsx_runtime43 = __toESM(require_jsx_runtime(), 1);
function FormPanelField({
  data,
  field,
  onChange,
  validity
}) {
  const layout = field.layout;
  if (layout.openAs === "modal") {
    return /* @__PURE__ */ (0, import_jsx_runtime43.jsx)(
      modal_default,
      {
        data,
        field,
        onChange,
        validity
      }
    );
  }
  return /* @__PURE__ */ (0, import_jsx_runtime43.jsx)(
    dropdown_default,
    {
      data,
      field,
      onChange,
      validity
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/card/index.mjs
var import_components24 = __toESM(require_components(), 1);
var import_compose4 = __toESM(require_compose(), 1);
var import_element29 = __toESM(require_element(), 1);
var import_i18n20 = __toESM(require_i18n(), 1);

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/validation-badge.mjs
var import_i18n19 = __toESM(require_i18n(), 1);
var import_jsx_runtime44 = __toESM(require_jsx_runtime(), 1);
function countInvalidFields(validity) {
  if (!validity) {
    return 0;
  }
  let count = 0;
  const validityRules = Object.keys(validity).filter(
    (key) => key !== "children"
  );
  for (const key of validityRules) {
    const rule = validity[key];
    if (rule?.type === "invalid") {
      count++;
    }
  }
  if (validity.children) {
    for (const childValidity of Object.values(validity.children)) {
      count += countInvalidFields(childValidity);
    }
  }
  return count;
}
function ValidationBadge({
  validity
}) {
  const invalidCount = countInvalidFields(validity);
  if (invalidCount === 0) {
    return null;
  }
  return /* @__PURE__ */ (0, import_jsx_runtime44.jsx)(Badge, { intent: "high", children: (0, import_i18n19.sprintf)(
    /* translators: %d: Number of fields that need attention */
    (0, import_i18n19._n)(
      "%d field needs attention",
      "%d fields need attention",
      invalidCount
    ),
    invalidCount
  ) });
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/card/index.mjs
var import_jsx_runtime45 = __toESM(require_jsx_runtime(), 1);
function isSummaryFieldVisible(summaryField, summaryConfig, isOpen) {
  if (!summaryConfig || Array.isArray(summaryConfig) && summaryConfig.length === 0) {
    return false;
  }
  const summaryConfigArray = Array.isArray(summaryConfig) ? summaryConfig : [summaryConfig];
  const fieldConfig = summaryConfigArray.find((config) => {
    if (typeof config === "string") {
      return config === summaryField.id;
    }
    if (typeof config === "object" && "id" in config) {
      return config.id === summaryField.id;
    }
    return false;
  });
  if (!fieldConfig) {
    return false;
  }
  if (typeof fieldConfig === "string") {
    return true;
  }
  if (typeof fieldConfig === "object" && "visibility" in fieldConfig) {
    return fieldConfig.visibility === "always" || fieldConfig.visibility === "when-collapsed" && !isOpen;
  }
  return true;
}
function FormCardField({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const { fields } = (0, import_element29.useContext)(dataform_context_default);
  const layout = field.layout;
  const cardBodyRef = (0, import_element29.useRef)(null);
  const bodyId = (0, import_compose4.useInstanceId)(
    FormCardField,
    "dataforms-layouts-card-card-body"
  );
  const form = (0, import_element29.useMemo)(
    () => ({
      layout: DEFAULT_LAYOUT,
      fields: field.children ?? []
    }),
    [field]
  );
  const { isOpened, isCollapsible } = layout;
  const [internalIsOpen, setIsOpen] = (0, import_element29.useState)(isOpened);
  const [touched, setTouched] = (0, import_element29.useState)(false);
  (0, import_element29.useEffect)(() => {
    setIsOpen(isOpened);
  }, [isOpened]);
  const toggle = (0, import_element29.useCallback)(() => {
    setIsOpen((prev) => {
      if (prev) {
        setTouched(true);
      }
      return !prev;
    });
  }, []);
  const isOpen = isCollapsible ? internalIsOpen : true;
  const handleBlur = (0, import_element29.useCallback)(() => {
    setTouched(true);
  }, [setTouched]);
  useReportValidity(cardBodyRef, isOpen && touched);
  const summaryFields = getSummaryFields(layout.summary, fields);
  const visibleSummaryFields = summaryFields.filter(
    (summaryField) => isSummaryFieldVisible(summaryField, layout.summary, isOpen)
  );
  const validationBadge = touched && layout.isCollapsible ? /* @__PURE__ */ (0, import_jsx_runtime45.jsx)(ValidationBadge, { validity }) : null;
  const sizeCard = {
    blockStart: "medium",
    blockEnd: "medium",
    inlineStart: "medium",
    inlineEnd: "medium"
  };
  let label = field.label;
  let withHeader;
  let bodyContent;
  if (field.children) {
    withHeader = !!label && layout.withHeader;
    bodyContent = /* @__PURE__ */ (0, import_jsx_runtime45.jsxs)(import_jsx_runtime45.Fragment, { children: [
      field.description && /* @__PURE__ */ (0, import_jsx_runtime45.jsx)("div", { className: "dataforms-layouts-card__field-description", children: field.description }),
      /* @__PURE__ */ (0, import_jsx_runtime45.jsx)(
        DataFormLayout,
        {
          data,
          form,
          onChange,
          validity: validity?.children
        }
      )
    ] });
  } else {
    const fieldDefinition = fields.find(
      (fieldDef) => fieldDef.id === field.id
    );
    if (!fieldDefinition || !fieldDefinition.Edit) {
      return null;
    }
    const SingleFieldLayout = getFormFieldLayout("regular")?.component;
    if (!SingleFieldLayout) {
      return null;
    }
    label = fieldDefinition.label;
    withHeader = !!label && layout.withHeader;
    bodyContent = /* @__PURE__ */ (0, import_jsx_runtime45.jsx)(
      SingleFieldLayout,
      {
        data,
        field,
        onChange,
        hideLabelFromVision: hideLabelFromVision || withHeader,
        markWhenOptional,
        validity
      }
    );
  }
  const sizeCardBody = {
    blockStart: withHeader ? "none" : "medium",
    blockEnd: "medium",
    inlineStart: "medium",
    inlineEnd: "medium"
  };
  return /* @__PURE__ */ (0, import_jsx_runtime45.jsxs)(import_components24.Card, { className: "dataforms-layouts-card__field", size: sizeCard, children: [
    withHeader && /* @__PURE__ */ (0, import_jsx_runtime45.jsxs)(
      import_components24.CardHeader,
      {
        className: "dataforms-layouts-card__field-header",
        onClick: isCollapsible ? toggle : void 0,
        style: {
          cursor: isCollapsible ? "pointer" : void 0
        },
        isBorderless: true,
        children: [
          /* @__PURE__ */ (0, import_jsx_runtime45.jsxs)(
            "div",
            {
              style: {
                // Match the expand/collapse button's height to avoid layout
                // differences when that button is not displayed.
                height: isCollapsible ? void 0 : "40px",
                width: "100%",
                display: "flex",
                justifyContent: "space-between",
                alignItems: "center"
              },
              children: [
                /* @__PURE__ */ (0, import_jsx_runtime45.jsx)("span", { className: "dataforms-layouts-card__field-header-label", children: label }),
                validationBadge,
                visibleSummaryFields.length > 0 && layout.withHeader && /* @__PURE__ */ (0, import_jsx_runtime45.jsx)("div", { className: "dataforms-layouts-card__field-summary", children: visibleSummaryFields.map(
                  (summaryField) => /* @__PURE__ */ (0, import_jsx_runtime45.jsx)(
                    summaryField.render,
                    {
                      item: data,
                      field: summaryField
                    },
                    summaryField.id
                  )
                ) })
              ]
            }
          ),
          isCollapsible && /* @__PURE__ */ (0, import_jsx_runtime45.jsx)(
            import_components24.Button,
            {
              __next40pxDefaultSize: true,
              variant: "tertiary",
              icon: isOpen ? chevron_up_default : chevron_down_default,
              "aria-expanded": isOpen,
              "aria-controls": bodyId,
              "aria-label": isOpen ? (0, import_i18n20.__)("Collapse") : (0, import_i18n20.__)("Expand")
            }
          )
        ]
      }
    ),
    (isOpen || !withHeader) && // If it doesn't have a header, keep it open.
    // Otherwise, the card will not be visible.
    /* @__PURE__ */ (0, import_jsx_runtime45.jsx)(
      import_components24.CardBody,
      {
        id: bodyId,
        size: sizeCardBody,
        className: "dataforms-layouts-card__field-control",
        ref: cardBodyRef,
        onBlur: handleBlur,
        children: bodyContent
      }
    )
  ] });
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/row/index.mjs
var import_components25 = __toESM(require_components(), 1);
var import_jsx_runtime46 = __toESM(require_jsx_runtime(), 1);
function Header2({ title }) {
  return /* @__PURE__ */ (0, import_jsx_runtime46.jsx)(
    Stack,
    {
      direction: "column",
      className: "dataforms-layouts-row__header",
      gap: "lg",
      children: /* @__PURE__ */ (0, import_jsx_runtime46.jsx)(Stack, { direction: "row", align: "center", children: /* @__PURE__ */ (0, import_jsx_runtime46.jsx)(import_components25.__experimentalHeading, { level: 2, size: 13, children: title }) })
    }
  );
}
var EMPTY_WRAPPER = ({ children }) => /* @__PURE__ */ (0, import_jsx_runtime46.jsx)(import_jsx_runtime46.Fragment, { children });
function FormRowField({
  data,
  field,
  onChange,
  hideLabelFromVision,
  markWhenOptional,
  validity
}) {
  const layout = field.layout;
  if (!!field.children) {
    const form = {
      layout: DEFAULT_LAYOUT,
      fields: field.children
    };
    return /* @__PURE__ */ (0, import_jsx_runtime46.jsxs)("div", { className: "dataforms-layouts-row__field", children: [
      !hideLabelFromVision && field.label && /* @__PURE__ */ (0, import_jsx_runtime46.jsx)(Header2, { title: field.label }),
      /* @__PURE__ */ (0, import_jsx_runtime46.jsx)(Stack, { direction: "row", align: layout.alignment, gap: "lg", children: /* @__PURE__ */ (0, import_jsx_runtime46.jsx)(
        DataFormLayout,
        {
          data,
          form,
          onChange,
          validity: validity?.children,
          as: EMPTY_WRAPPER,
          children: (FieldLayout, childField, childFieldValidity) => /* @__PURE__ */ (0, import_jsx_runtime46.jsx)(
            "div",
            {
              className: "dataforms-layouts-row__field-control",
              style: layout.styles[childField.id],
              children: /* @__PURE__ */ (0, import_jsx_runtime46.jsx)(
                FieldLayout,
                {
                  data,
                  field: childField,
                  onChange,
                  hideLabelFromVision,
                  markWhenOptional,
                  validity: childFieldValidity
                }
              )
            },
            childField.id
          )
        }
      ) })
    ] });
  }
  const RegularLayout = getFormFieldLayout("regular")?.component;
  if (!RegularLayout) {
    return null;
  }
  return /* @__PURE__ */ (0, import_jsx_runtime46.jsx)(import_jsx_runtime46.Fragment, { children: /* @__PURE__ */ (0, import_jsx_runtime46.jsx)("div", { className: "dataforms-layouts-row__field-control", children: /* @__PURE__ */ (0, import_jsx_runtime46.jsx)(
    RegularLayout,
    {
      data,
      field,
      onChange,
      markWhenOptional,
      validity
    }
  ) }) });
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/details/index.mjs
var import_element30 = __toESM(require_element(), 1);
var import_i18n21 = __toESM(require_i18n(), 1);
var import_jsx_runtime47 = __toESM(require_jsx_runtime(), 1);
function FormDetailsField({
  data,
  field,
  onChange,
  validity
}) {
  const { fields } = (0, import_element30.useContext)(dataform_context_default);
  const detailsRef = (0, import_element30.useRef)(null);
  const contentRef = (0, import_element30.useRef)(null);
  const [touched, setTouched] = (0, import_element30.useState)(false);
  const [isOpen, setIsOpen] = (0, import_element30.useState)(false);
  const form = (0, import_element30.useMemo)(
    () => ({
      layout: DEFAULT_LAYOUT,
      fields: field.children ?? []
    }),
    [field]
  );
  (0, import_element30.useEffect)(() => {
    const details = detailsRef.current;
    if (!details) {
      return;
    }
    const handleToggle = () => {
      const nowOpen = details.open;
      if (!nowOpen) {
        setTouched(true);
      }
      setIsOpen(nowOpen);
    };
    details.addEventListener("toggle", handleToggle);
    return () => {
      details.removeEventListener("toggle", handleToggle);
    };
  }, []);
  useReportValidity(contentRef, isOpen && touched);
  const handleBlur = (0, import_element30.useCallback)(() => {
    setTouched(true);
  }, []);
  if (!field.children) {
    return null;
  }
  const summaryFieldId = field.layout.summary ?? "";
  const summaryField = summaryFieldId ? fields.find((fieldDef) => fieldDef.id === summaryFieldId) : void 0;
  let summaryContent;
  if (summaryField && summaryField.render) {
    summaryContent = /* @__PURE__ */ (0, import_jsx_runtime47.jsx)(summaryField.render, { item: data, field: summaryField });
  } else {
    summaryContent = field.label || (0, import_i18n21.__)("More details");
  }
  return /* @__PURE__ */ (0, import_jsx_runtime47.jsxs)(
    "details",
    {
      ref: detailsRef,
      className: "dataforms-layouts-details__details",
      children: [
        /* @__PURE__ */ (0, import_jsx_runtime47.jsx)("summary", { className: "dataforms-layouts-details__summary", children: /* @__PURE__ */ (0, import_jsx_runtime47.jsxs)(
          Stack,
          {
            direction: "row",
            align: "center",
            gap: "md",
            className: "dataforms-layouts-details__summary-content",
            children: [
              summaryContent,
              touched && /* @__PURE__ */ (0, import_jsx_runtime47.jsx)(ValidationBadge, { validity })
            ]
          }
        ) }),
        /* @__PURE__ */ (0, import_jsx_runtime47.jsx)(
          "div",
          {
            ref: contentRef,
            className: "dataforms-layouts-details__content",
            onBlur: handleBlur,
            children: /* @__PURE__ */ (0, import_jsx_runtime47.jsx)(
              DataFormLayout,
              {
                data,
                form,
                onChange,
                validity: validity?.children
              }
            )
          }
        )
      ]
    }
  );
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/index.mjs
var import_jsx_runtime48 = __toESM(require_jsx_runtime(), 1);
var FORM_FIELD_LAYOUTS = [
  {
    type: "regular",
    component: FormRegularField,
    wrapper: ({ children }) => /* @__PURE__ */ (0, import_jsx_runtime48.jsx)(
      Stack,
      {
        direction: "column",
        className: "dataforms-layouts__wrapper",
        gap: "lg",
        children
      }
    )
  },
  {
    type: "panel",
    component: FormPanelField,
    wrapper: ({ children }) => /* @__PURE__ */ (0, import_jsx_runtime48.jsx)(
      Stack,
      {
        direction: "column",
        className: "dataforms-layouts__wrapper",
        gap: "md",
        children
      }
    )
  },
  {
    type: "card",
    component: FormCardField,
    wrapper: ({ children }) => /* @__PURE__ */ (0, import_jsx_runtime48.jsx)(
      Stack,
      {
        direction: "column",
        className: "dataforms-layouts__wrapper",
        gap: "xl",
        children
      }
    )
  },
  {
    type: "row",
    component: FormRowField,
    wrapper: ({
      children,
      layout
    }) => /* @__PURE__ */ (0, import_jsx_runtime48.jsx)(
      Stack,
      {
        direction: "column",
        className: "dataforms-layouts__wrapper",
        gap: "lg",
        children: /* @__PURE__ */ (0, import_jsx_runtime48.jsx)("div", { className: "dataforms-layouts-row__field", children: /* @__PURE__ */ (0, import_jsx_runtime48.jsx)(
          Stack,
          {
            direction: "row",
            gap: "lg",
            align: layout.alignment,
            children
          }
        ) })
      }
    )
  },
  {
    type: "details",
    component: FormDetailsField
  }
];
function getFormFieldLayout(type) {
  return FORM_FIELD_LAYOUTS.find((layout) => layout.type === type);
}

// node_modules/@wordpress/dataviews/build-module/components/dataform-layouts/data-form-layout.mjs
var import_jsx_runtime49 = __toESM(require_jsx_runtime(), 1);
var DEFAULT_WRAPPER = ({ children }) => /* @__PURE__ */ (0, import_jsx_runtime49.jsx)(Stack, { direction: "column", className: "dataforms-layouts__wrapper", gap: "lg", children });
function DataFormLayout({
  data,
  form,
  onChange,
  validity,
  children,
  as
}) {
  const { fields: fieldDefinitions } = (0, import_element31.useContext)(dataform_context_default);
  const markWhenOptional = (0, import_element31.useMemo)(() => {
    const requiredCount = fieldDefinitions.filter(
      (f2) => !!f2.isValid?.required
    ).length;
    const optionalCount = fieldDefinitions.length - requiredCount;
    return requiredCount > optionalCount;
  }, [fieldDefinitions]);
  function getFieldDefinition2(field) {
    return fieldDefinitions.find(
      (fieldDefinition) => fieldDefinition.id === field.id
    );
  }
  const Wrapper = as ?? getFormFieldLayout(form.layout.type)?.wrapper ?? DEFAULT_WRAPPER;
  return /* @__PURE__ */ (0, import_jsx_runtime49.jsx)(Wrapper, { layout: form.layout, children: form.fields.map((formField) => {
    const FieldLayout = getFormFieldLayout(formField.layout.type)?.component;
    if (!FieldLayout) {
      return null;
    }
    const fieldDefinition = !formField.children ? getFieldDefinition2(formField) : void 0;
    if (fieldDefinition && fieldDefinition.isVisible && !fieldDefinition.isVisible(data)) {
      return null;
    }
    if (children) {
      return children(
        FieldLayout,
        formField,
        validity?.[formField.id],
        markWhenOptional
      );
    }
    return /* @__PURE__ */ (0, import_jsx_runtime49.jsx)(
      FieldLayout,
      {
        data,
        field: formField,
        onChange,
        markWhenOptional,
        validity: validity?.[formField.id]
      },
      formField.id
    );
  }) });
}

// node_modules/@wordpress/dataviews/build-module/dataform/index.mjs
var import_jsx_runtime50 = __toESM(require_jsx_runtime(), 1);
function DataForm({
  data,
  form,
  fields,
  onChange,
  validity
}) {
  const normalizedForm = (0, import_element32.useMemo)(() => normalize_form_default(form), [form]);
  const normalizedFields = (0, import_element32.useMemo)(
    () => normalizeFields(fields),
    [fields]
  );
  if (!form.fields) {
    return null;
  }
  return /* @__PURE__ */ (0, import_jsx_runtime50.jsx)(DataFormProvider, { fields: normalizedFields, children: /* @__PURE__ */ (0, import_jsx_runtime50.jsx)(
    DataFormLayout,
    {
      data,
      form: normalizedForm,
      onChange,
      validity
    }
  ) });
}

// assets/js/admin-settings/content.js
var import_jsx_runtime51 = __toESM(require_jsx_runtime());
var CONFIG = window.tenupExperienceSettingsData ?? {};
var OPT_ALLOW_SSO = "tenup_allow_sso";
var OPT_STRONG_PASSWORDS = "tenup_require_strong_passwords";
var OPT_RESTRICT_REST_API = "tenup_restrict_rest_api";
var OPT_DISABLE_COMMENTS = "tenup_disable_comments";
var OPT_DISABLE_GUTENBERG = "tenup_disable_gutenberg";
var OPT_PASSWORD_PROTECT = "tenup_password_protect";
var OPT_MONITOR = "tenup_support_monitor_settings";
var SaveButton = () => {
  const [isSaveOpen, setIsSaveOpen] = (0, import_element33.useState)(false);
  const { dirtyEntityRecords } = (0, import_data.useSelect)(
    (select) => ({
      dirtyEntityRecords: select("core").__experimentalGetDirtyEntityRecords()
    }),
    []
  );
  const isDirty = dirtyEntityRecords.length > 0;
  return /* @__PURE__ */ (0, import_jsx_runtime51.jsxs)(import_jsx_runtime51.Fragment, { children: [
    /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(
      import_components26.Button,
      {
        variant: "primary",
        disabled: !isDirty,
        "aria-disabled": !isDirty,
        onClick: () => setIsSaveOpen(true),
        children: isDirty ? (0, import_i18n22.sprintf)(
          /* translators: %d: number of unsaved changes */
          (0, import_i18n22.__)("Review %d changes\u2026", "tenup"),
          dirtyEntityRecords.length
        ) : (0, import_i18n22.__)("Saved", "tenup")
      }
    ),
    isSaveOpen && /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(
      import_components26.Modal,
      {
        className: "edit-site-save-panel__modal",
        onRequestClose: () => setIsSaveOpen(false),
        title: (0, import_i18n22.__)("Review changes", "tenup"),
        size: "small",
        children: /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(import_editor.EntitiesSavedStates, { close: () => setIsSaveOpen(false) })
      }
    )
  ] });
};
var AdminPageLayout = ({ title, children }) => /* @__PURE__ */ (0, import_jsx_runtime51.jsxs)("div", { style: { display: "flex", flexDirection: "column", height: "100%" }, children: [
  /* @__PURE__ */ (0, import_jsx_runtime51.jsxs)("header", { className: "tenup-boot-header", children: [
    CONFIG.logoUrl && /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(
      "img",
      {
        className: "tenup-boot-header__logo",
        src: CONFIG.logoUrl,
        alt: (0, import_i18n22.__)("Fueled", "tenup")
      }
    ),
    /* @__PURE__ */ (0, import_jsx_runtime51.jsx)("h2", { children: title })
  ] }),
  /* @__PURE__ */ (0, import_jsx_runtime51.jsx)("div", { className: "tenup-boot-content", children }),
  /* @__PURE__ */ (0, import_jsx_runtime51.jsx)("footer", { className: "tenup-boot-footer", children: /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(SaveButton, {}) })
] });
var SettingsCard = ({ title, children }) => /* @__PURE__ */ (0, import_jsx_runtime51.jsx)("div", { className: "dataforms-layouts__wrapper", children: /* @__PURE__ */ (0, import_jsx_runtime51.jsxs)(import_components26.Card, { className: "dataforms-layouts-card__field", children: [
  /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(import_components26.CardHeader, { className: "dataforms-layouts-card__field-header", children: /* @__PURE__ */ (0, import_jsx_runtime51.jsx)("span", { className: "dataforms-layouts-card__field-header-label", children: title }) }),
  /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(import_components26.CardBody, { className: "dataforms-layouts-card__field-control", children })
] }) });
var Note = ({ tone = "info", children }) => /* @__PURE__ */ (0, import_jsx_runtime51.jsx)("div", { className: `tenup-boot-note is-${tone}`, children: /* @__PURE__ */ (0, import_jsx_runtime51.jsx)("p", { children }) });
var YES_NO = [
  { value: "yes", label: (0, import_i18n22.__)("Yes", "tenup") },
  { value: "no", label: (0, import_i18n22.__)("No", "tenup") }
];
var ON_OFF = [
  { value: "1", label: (0, import_i18n22.__)("Yes", "tenup") },
  { value: "0", label: (0, import_i18n22.__)("No", "tenup") }
];
var SECURITY_FIELDS = [
  {
    id: "allowSso",
    label: (0, import_i18n22.__)("Allow Fueled SSO", "tenup"),
    description: (0, import_i18n22.__)(
      "Allows members of Fueled on your project team to log in via SSO. This is extremely important to streamline maintenance of your website.",
      "tenup"
    ),
    type: "string",
    Edit: "radio",
    elements: YES_NO
  },
  {
    id: "strongPasswords",
    label: (0, import_i18n22.__)("Require Strong Passwords", "tenup"),
    description: (0, import_i18n22.__)(
      "Requires stronger passwords and checks them against breach data during sign-in or password changes.",
      "tenup"
    ),
    type: "string",
    Edit: "radio",
    elements: ON_OFF
  },
  {
    id: "restrictRestApi",
    label: (0, import_i18n22.__)("REST API Availability", "tenup"),
    description: (0, import_i18n22.__)(
      "Restricting REST API access helps prevent unintended exposure of potentially sensitive information.",
      "tenup"
    ),
    type: "string",
    Edit: "radio",
    elements: [
      { value: "all", label: (0, import_i18n22.__)("Restrict all access to authenticated users", "tenup") },
      {
        value: "users",
        label: (0, import_i18n22.__)("Restrict access to the users endpoint to authenticated users", "tenup")
      },
      { value: "none", label: (0, import_i18n22.__)("Publicly accessible", "tenup") }
    ]
  }
];
var EDITORIAL_FIELDS = [
  {
    id: "disableComments",
    label: (0, import_i18n22.__)("Disable Comments", "tenup"),
    description: (0, import_i18n22.__)(
      "Removes all comment-related UI from the admin and front end. Block Notes remain available.",
      "tenup"
    ),
    type: "string",
    Edit: "radio",
    elements: YES_NO
  },
  {
    id: "disableGutenberg",
    label: (0, import_i18n22.__)("Use Classic Editor", "tenup"),
    description: (0, import_i18n22.__)(
      "Disables the block editor in favor of the prior writing experience.",
      "tenup"
    ),
    type: "string",
    Edit: "radio",
    elements: ON_OFF
  },
  {
    id: "passwordProtect",
    label: (0, import_i18n22.__)("Enable Password Protected Content", "tenup"),
    description: (0, import_i18n22.__)(
      "WordPress default password protected post functionality is insecure and does not work with page caching.",
      "tenup"
    ),
    type: "string",
    Edit: "radio",
    elements: ON_OFF
  }
];
var MONITOR_FIELDS = [
  {
    id: "monitorEnable",
    label: (0, import_i18n22.__)("Enable Support Monitor", "tenup"),
    type: "string",
    Edit: "radio",
    elements: YES_NO
  },
  {
    id: "monitorApiKey",
    label: (0, import_i18n22.__)("API Key", "tenup"),
    type: "string",
    Edit: "text"
  },
  {
    id: "monitorServerUrl",
    label: (0, import_i18n22.__)("API Server URL", "tenup"),
    description: (0, import_i18n22.__)("Only shown while Support Monitor debugging is enabled.", "tenup"),
    type: "string",
    Edit: "text"
  }
];
var visibleForm = (fields, hidden = []) => ({
  fields: fields.map((f2) => f2.id).filter((id) => !hidden.includes(id))
});
var SecurityCard = ({ formData, onChange }) => {
  const hidden = [];
  if (!CONFIG.sso?.available) {
    hidden.push("allowSso");
  }
  if (!CONFIG.strongPasswords?.available) {
    hidden.push("strongPasswords");
  }
  if (!CONFIG.restApi?.available) {
    hidden.push("restrictRestApi");
  }
  return /* @__PURE__ */ (0, import_jsx_runtime51.jsxs)(SettingsCard, { title: (0, import_i18n22.__)("Security & Access", "tenup"), children: [
    !CONFIG.sso?.available && /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(Note, { children: (0, import_i18n22.__)("Fueled SSO has been disabled in code for this site.", "tenup") }),
    /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(
      DataForm,
      {
        data: formData,
        fields: SECURITY_FIELDS,
        form: visibleForm(SECURITY_FIELDS, hidden),
        onChange
      }
    )
  ] });
};
var EditorialCard = ({ formData, onChange }) => {
  const hidden = [];
  if (CONFIG.comments?.locked) {
    hidden.push("disableComments");
  }
  return /* @__PURE__ */ (0, import_jsx_runtime51.jsxs)(SettingsCard, { title: (0, import_i18n22.__)("Editorial", "tenup"), children: [
    CONFIG.comments?.locked && /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(Note, { children: (0, import_i18n22.__)(
      "The comments setting is controlled in code for this site and cannot be changed here.",
      "tenup"
    ) }),
    /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(
      DataForm,
      {
        data: formData,
        fields: EDITORIAL_FIELDS,
        form: visibleForm(EDITORIAL_FIELDS, hidden),
        onChange
      }
    )
  ] });
};
var MonitorCard = ({ formData, onChange }) => {
  const hidden = [];
  if (CONFIG.monitor?.enableLocked) {
    hidden.push("monitorEnable");
  }
  if (CONFIG.monitor?.apiKeyLocked) {
    hidden.push("monitorApiKey");
  }
  if (!CONFIG.monitor?.serverUrlVisible) {
    hidden.push("monitorServerUrl");
  }
  return /* @__PURE__ */ (0, import_jsx_runtime51.jsxs)(SettingsCard, { title: (0, import_i18n22.__)("Support Monitor", "tenup"), children: [
    /* @__PURE__ */ (0, import_jsx_runtime51.jsx)("p", { style: { marginTop: 0 }, children: (0, import_i18n22.__)(
      "Fueled collects site-health information, including plugin, WordPress, and system versions, general site issues, and Fueled team accounts associated with the site, to provide proactive support. It does not send proprietary site content or customer account information. Although recommended, this functionality is optional and can be disabled.",
      "tenup"
    ) }),
    CONFIG.monitor?.isLocalEnvironment && /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(Note, { children: (0, import_i18n22.__)(
      "Local environment detected: support monitoring is automatically disabled in local environments to prevent test/development data from being sent to the monitoring service.",
      "tenup"
    ) }),
    (CONFIG.monitor?.enableLocked || CONFIG.monitor?.apiKeyLocked) && /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(Note, { children: (0, import_i18n22.__)(
      "Some Support Monitor settings are defined in code for this site and cannot be changed here.",
      "tenup"
    ) }),
    /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(
      DataForm,
      {
        data: formData,
        fields: MONITOR_FIELDS,
        form: visibleForm(MONITOR_FIELDS, hidden),
        onChange
      }
    )
  ] });
};
var SettingsStage = () => {
  const siteSettings = (0, import_data.useSelect)(
    (select) => select("core").getEditedEntityRecord("root", "site"),
    []
  );
  const hasResolved = (0, import_data.useSelect)(
    (select) => select("core").hasFinishedResolution("getEntityRecord", ["root", "site"]),
    []
  );
  const { editEntityRecord } = (0, import_data.useDispatch)("core");
  const monitorSettings = (0, import_element33.useMemo)(
    () => ({
      enable_support_monitor: "no",
      api_key: "",
      server_url: "",
      ...siteSettings?.[OPT_MONITOR] ?? {}
    }),
    [siteSettings]
  );
  const formData = (0, import_element33.useMemo)(
    () => ({
      allowSso: siteSettings?.[OPT_ALLOW_SSO] ?? "yes",
      strongPasswords: String(siteSettings?.[OPT_STRONG_PASSWORDS] ?? 1),
      restrictRestApi: siteSettings?.[OPT_RESTRICT_REST_API] ?? "users",
      disableComments: siteSettings?.[OPT_DISABLE_COMMENTS] || "no",
      disableGutenberg: String(siteSettings?.[OPT_DISABLE_GUTENBERG] ?? 0),
      passwordProtect: String(siteSettings?.[OPT_PASSWORD_PROTECT] ?? 0),
      monitorEnable: monitorSettings.enable_support_monitor || "no",
      monitorApiKey: monitorSettings.api_key ?? "",
      monitorServerUrl: monitorSettings.server_url ?? ""
    }),
    [siteSettings, monitorSettings]
  );
  const onChange = (0, import_element33.useCallback)(
    (edits) => {
      const mapped = {};
      if ("allowSso" in edits) {
        mapped[OPT_ALLOW_SSO] = edits.allowSso;
      }
      if ("strongPasswords" in edits) {
        mapped[OPT_STRONG_PASSWORDS] = parseInt(edits.strongPasswords, 10);
      }
      if ("restrictRestApi" in edits) {
        mapped[OPT_RESTRICT_REST_API] = edits.restrictRestApi;
      }
      if ("disableComments" in edits) {
        mapped[OPT_DISABLE_COMMENTS] = edits.disableComments;
      }
      if ("disableGutenberg" in edits) {
        mapped[OPT_DISABLE_GUTENBERG] = parseInt(edits.disableGutenberg, 10);
      }
      if ("passwordProtect" in edits) {
        mapped[OPT_PASSWORD_PROTECT] = parseInt(edits.passwordProtect, 10);
      }
      const monitorEdits = {};
      if ("monitorEnable" in edits) {
        monitorEdits.enable_support_monitor = edits.monitorEnable;
      }
      if ("monitorApiKey" in edits) {
        monitorEdits.api_key = edits.monitorApiKey;
      }
      if ("monitorServerUrl" in edits) {
        monitorEdits.server_url = edits.monitorServerUrl;
      }
      if (Object.keys(monitorEdits).length) {
        const merged = { ...monitorSettings, ...monitorEdits };
        if (!merged.server_url) {
          delete merged.server_url;
        }
        mapped[OPT_MONITOR] = merged;
      }
      editEntityRecord("root", "site", void 0, mapped);
    },
    [editEntityRecord, monitorSettings]
  );
  if (!hasResolved) {
    return /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(AdminPageLayout, { title: (0, import_i18n22.__)("Fueled Experience", "tenup"), children: /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(import_components26.Spinner, {}) });
  }
  return /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(AdminPageLayout, { title: (0, import_i18n22.__)("Fueled Experience", "tenup"), children: /* @__PURE__ */ (0, import_jsx_runtime51.jsxs)("section", { children: [
    /* @__PURE__ */ (0, import_jsx_runtime51.jsx)("h2", { className: "tenup-boot-section-title", children: (0, import_i18n22.__)("Plugin settings", "tenup") }),
    /* @__PURE__ */ (0, import_jsx_runtime51.jsx)("p", { className: "tenup-boot-section-description", children: (0, import_i18n22.__)(
      "The Fueled Experience plugin configures WordPress to help protect and support this site according to Fueled\u2019s best practices. These settings are also available on the classic General, Writing, and Reading settings screens.",
      "tenup"
    ) }),
    /* @__PURE__ */ (0, import_jsx_runtime51.jsxs)("div", { className: "tenup-boot-cards", children: [
      /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(SecurityCard, { formData, onChange }),
      /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(EditorialCard, { formData, onChange }),
      /* @__PURE__ */ (0, import_jsx_runtime51.jsx)(MonitorCard, { formData, onChange })
    ] })
  ] }) });
};
var stage = SettingsStage;
export {
  stage
};
