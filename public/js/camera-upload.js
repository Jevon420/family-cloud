/******/ (() => { // webpackBootstrap
/*!***************************************!*\
  !*** ./resources/js/camera-upload.js ***!
  \***************************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { if (r) i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n;else { var o = function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); }; o("next", 0), o("throw", 1), o("return", 2); } }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var CameraUpload = /*#__PURE__*/function () {
  function CameraUpload() {
    _classCallCheck(this, CameraUpload);
    this.modal = document.getElementById('cameraUploadModal');
    this.video = document.getElementById('cameraPreview');
    this.canvas = document.getElementById('captureCanvas');
    this.context = this.canvas.getContext('2d');
    this.gallerySelect = document.getElementById('gallerySelect');
    this.cameraError = document.getElementById('cameraError');
    this.stream = null;
    this.capturedImage = null;
    this.initEventListeners();
    this.loadGalleries();
  }
  return _createClass(CameraUpload, [{
    key: "initEventListeners",
    value: function initEventListeners() {
      var _document$getElementB,
        _this = this,
        _document$getElementB2,
        _document$getElementB3,
        _document$getElementB4,
        _document$getElementB5,
        _document$getElementB6,
        _document$getElementB7,
        _this$modal;
      // Modal triggers
      (_document$getElementB = document.getElementById('cameraUploadBtn')) === null || _document$getElementB === void 0 || _document$getElementB.addEventListener('click', function () {
        return _this.openModal();
      });
      (_document$getElementB2 = document.getElementById('cameraUploadBtnMobile')) === null || _document$getElementB2 === void 0 || _document$getElementB2.addEventListener('click', function () {
        return _this.openModal();
      });
      (_document$getElementB3 = document.getElementById('closeCameraModal')) === null || _document$getElementB3 === void 0 || _document$getElementB3.addEventListener('click', function () {
        return _this.closeModal();
      });

      // Camera controls
      (_document$getElementB4 = document.getElementById('startCameraBtn')) === null || _document$getElementB4 === void 0 || _document$getElementB4.addEventListener('click', function () {
        return _this.startCamera();
      });
      (_document$getElementB5 = document.getElementById('captureBtn')) === null || _document$getElementB5 === void 0 || _document$getElementB5.addEventListener('click', function () {
        return _this.capturePhoto();
      });
      (_document$getElementB6 = document.getElementById('retakeBtn')) === null || _document$getElementB6 === void 0 || _document$getElementB6.addEventListener('click', function () {
        return _this.retakePhoto();
      });
      (_document$getElementB7 = document.getElementById('uploadBtn')) === null || _document$getElementB7 === void 0 || _document$getElementB7.addEventListener('click', function () {
        return _this.uploadPhoto();
      });

      // Close modal when clicking outside
      (_this$modal = this.modal) === null || _this$modal === void 0 || _this$modal.addEventListener('click', function (e) {
        if (e.target === _this.modal) {
          _this.closeModal();
        }
      });
    }
  }, {
    key: "loadGalleries",
    value: function () {
      var _loadGalleries = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee() {
        var _document$querySelect, response, data, _t;
        return _regenerator().w(function (_context) {
          while (1) switch (_context.n) {
            case 0:
              _context.p = 0;
              _context.n = 1;
              return fetch('/camera-upload/galleries', {
                headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'X-CSRF-TOKEN': ((_document$querySelect = document.querySelector('meta[name="csrf-token"]')) === null || _document$querySelect === void 0 ? void 0 : _document$querySelect.content) || ''
                }
              });
            case 1:
              response = _context.v;
              if (response.ok) {
                _context.n = 2;
                break;
              }
              throw new Error('Failed to load galleries');
            case 2:
              _context.n = 3;
              return response.json();
            case 3:
              data = _context.v;
              this.populateGalleries(data.galleries);
              _context.n = 5;
              break;
            case 4:
              _context.p = 4;
              _t = _context.v;
              console.error('Error loading galleries:', _t);
              this.gallerySelect.innerHTML = '<option value="">Error loading galleries</option>';
            case 5:
              return _context.a(2);
          }
        }, _callee, this, [[0, 4]]);
      }));
      function loadGalleries() {
        return _loadGalleries.apply(this, arguments);
      }
      return loadGalleries;
    }()
  }, {
    key: "populateGalleries",
    value: function populateGalleries(galleries) {
      var _this2 = this;
      this.gallerySelect.innerHTML = '<option value="">Select a gallery...</option>';
      galleries.forEach(function (gallery) {
        var option = document.createElement('option');
        option.value = gallery.id;
        option.textContent = gallery.name;
        _this2.gallerySelect.appendChild(option);
      });
    }
  }, {
    key: "openModal",
    value: function openModal() {
      this.modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }
  }, {
    key: "closeModal",
    value: function closeModal() {
      this.modal.classList.add('hidden');
      document.body.style.overflow = '';
      this.stopCamera();
      this.resetUI();
    }
  }, {
    key: "startCamera",
    value: function () {
      var _startCamera = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2() {
        var constraints, _t2;
        return _regenerator().w(function (_context2) {
          while (1) switch (_context2.n) {
            case 0:
              _context2.p = 0;
              if (!(!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia)) {
                _context2.n = 1;
                break;
              }
              throw new Error('Camera not supported by this browser');
            case 1:
              // Request camera access with better mobile support
              constraints = {
                video: {
                  facingMode: 'environment',
                  // Try to use back camera on mobile
                  width: {
                    ideal: 1280
                  },
                  height: {
                    ideal: 720
                  }
                }
              }; // For iOS devices, add specific constraints
              if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
                constraints.video.width = {
                  ideal: 1920,
                  max: 1920
                };
                constraints.video.height = {
                  ideal: 1080,
                  max: 1080
                };
              }
              _context2.n = 2;
              return navigator.mediaDevices.getUserMedia(constraints);
            case 2:
              this.stream = _context2.v;
              this.video.srcObject = this.stream;
              this.video.play();

              // Show video and capture button
              this.video.classList.remove('hidden');
              this.cameraError.classList.add('hidden');
              document.getElementById('startCameraBtn').classList.add('hidden');
              document.getElementById('captureBtn').classList.remove('hidden');
              _context2.n = 4;
              break;
            case 3:
              _context2.p = 3;
              _t2 = _context2.v;
              console.error('Error accessing camera:', _t2);
              this.showCameraError('Unable to access camera. Please ensure you have granted camera permissions.');
            case 4:
              return _context2.a(2);
          }
        }, _callee2, this, [[0, 3]]);
      }));
      function startCamera() {
        return _startCamera.apply(this, arguments);
      }
      return startCamera;
    }()
  }, {
    key: "stopCamera",
    value: function stopCamera() {
      if (this.stream) {
        this.stream.getTracks().forEach(function (track) {
          return track.stop();
        });
        this.stream = null;
      }
    }
  }, {
    key: "capturePhoto",
    value: function capturePhoto() {
      if (!this.stream) return;

      // Set canvas dimensions to match video
      this.canvas.width = this.video.videoWidth;
      this.canvas.height = this.video.videoHeight;

      // Draw video frame to canvas
      this.context.drawImage(this.video, 0, 0);

      // Convert canvas to data URL
      this.capturedImage = this.canvas.toDataURL('image/jpeg', 0.9);

      // Show captured image and controls
      this.video.classList.add('hidden');
      this.canvas.classList.remove('hidden');
      document.getElementById('captureBtn').classList.add('hidden');
      document.getElementById('retakeBtn').classList.remove('hidden');
      document.getElementById('uploadBtn').classList.remove('hidden');
      this.stopCamera();
    }
  }, {
    key: "retakePhoto",
    value: function retakePhoto() {
      this.capturedImage = null;
      this.canvas.classList.add('hidden');
      document.getElementById('retakeBtn').classList.add('hidden');
      document.getElementById('uploadBtn').classList.add('hidden');
      document.getElementById('startCameraBtn').classList.remove('hidden');
    }
  }, {
    key: "uploadPhoto",
    value: function () {
      var _uploadPhoto = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee3() {
        var uploadProgress, uploadProgressBar, _document$querySelect2, formData, response, result, _t3;
        return _regenerator().w(function (_context3) {
          while (1) switch (_context3.n) {
            case 0:
              if (this.capturedImage) {
                _context3.n = 1;
                break;
              }
              alert('No photo captured');
              return _context3.a(2);
            case 1:
              if (this.gallerySelect.value) {
                _context3.n = 2;
                break;
              }
              alert('Please select a gallery');
              return _context3.a(2);
            case 2:
              uploadProgress = document.getElementById('uploadProgress');
              uploadProgressBar = document.getElementById('uploadProgressBar');
              _context3.p = 3;
              // Show progress
              uploadProgress.classList.remove('hidden');
              uploadProgressBar.style.width = '10%';
              formData = new FormData();
              formData.append('gallery_id', this.gallerySelect.value);
              formData.append('photo', this.capturedImage);
              formData.append('name', "Camera Upload ".concat(new Date().toLocaleString()));
              uploadProgressBar.style.width = '30%';
              _context3.n = 4;
              return fetch('/camera-upload/upload', {
                method: 'POST',
                body: formData,
                headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'X-CSRF-TOKEN': ((_document$querySelect2 = document.querySelector('meta[name="csrf-token"]')) === null || _document$querySelect2 === void 0 ? void 0 : _document$querySelect2.content) || ''
                }
              });
            case 4:
              response = _context3.v;
              uploadProgressBar.style.width = '70%';
              if (response.ok) {
                _context3.n = 5;
                break;
              }
              throw new Error('Upload failed');
            case 5:
              _context3.n = 6;
              return response.json();
            case 6:
              result = _context3.v;
              uploadProgressBar.style.width = '100%';
              if (!result.success) {
                _context3.n = 7;
                break;
              }
              // Show success message
              alert("Photo uploaded successfully to ".concat(result.photo.gallery, "!"));
              this.closeModal();

              // Optionally redirect to gallery or refresh page
              if (window.location.pathname.includes('/galleries/')) {
                window.location.reload();
              }
              _context3.n = 8;
              break;
            case 7:
              throw new Error(result.message || 'Upload failed');
            case 8:
              _context3.n = 10;
              break;
            case 9:
              _context3.p = 9;
              _t3 = _context3.v;
              console.error('Upload error:', _t3);
              alert('Failed to upload photo: ' + _t3.message);
            case 10:
              _context3.p = 10;
              uploadProgress.classList.add('hidden');
              uploadProgressBar.style.width = '0%';
              return _context3.f(10);
            case 11:
              return _context3.a(2);
          }
        }, _callee3, this, [[3, 9, 10, 11]]);
      }));
      function uploadPhoto() {
        return _uploadPhoto.apply(this, arguments);
      }
      return uploadPhoto;
    }()
  }, {
    key: "showCameraError",
    value: function showCameraError(message) {
      this.cameraError.classList.remove('hidden');
      this.cameraError.querySelector('p').textContent = message;
      this.video.classList.add('hidden');
    }
  }, {
    key: "resetUI",
    value: function resetUI() {
      // Reset all UI elements to initial state
      this.video.classList.add('hidden');
      this.canvas.classList.add('hidden');
      this.cameraError.classList.add('hidden');
      document.getElementById('startCameraBtn').classList.remove('hidden');
      document.getElementById('captureBtn').classList.add('hidden');
      document.getElementById('retakeBtn').classList.add('hidden');
      document.getElementById('uploadBtn').classList.add('hidden');
      document.getElementById('uploadProgress').classList.add('hidden');
      this.capturedImage = null;
    }
  }]);
}(); // Initialize camera upload when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
  if (document.getElementById('cameraUploadModal')) {
    new CameraUpload();
  }
});
/******/ })()
;