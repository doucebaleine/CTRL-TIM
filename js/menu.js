// Simple off-canvas menu toggle
(function(){
  function $(sel){return document.querySelector(sel)}
  function $all(sel){return Array.prototype.slice.call(document.querySelectorAll(sel))}

  var btn = $('.menu-burger');
  var off = $('#offcanvasMenu');
  var closeBtn = off && off.querySelector('.offcanvas-close');
  var focusableSelectors = 'a[href], button:not([disabled]), input, textarea, select, [tabindex]:not([tabindex="-1"])';
  var lastFocused = null;
  var backdrop = $('#offcanvasBackdrop');
  var sentinelTop = off && off.querySelector('.sentinel-top');
  var sentinelBottom = off && off.querySelector('.sentinel-bottom');

  function trapFocus(container){
    var focusable = $all(focusableSelectors).filter(function(el){ return container.contains(el); });
    if(!focusable.length) return;
    var first = focusable[0];
    var last = focusable[focusable.length-1];

    function keyHandler(e){
      if(e.key !== 'Tab') return;
      if(e.shiftKey && document.activeElement === first){
        e.preventDefault(); last.focus();
      } else if(!e.shiftKey && document.activeElement === last){
        e.preventDefault(); first.focus();
      }
    }

    container._keyHandler = keyHandler;
    document.addEventListener('keydown', keyHandler);
    first.focus();
  }

  function releaseFocus(container){
    if(container && container._keyHandler) document.removeEventListener('keydown', container._keyHandler);
  }

  function openMenu(){
    if(!off) return;
    lastFocused = document.activeElement;
    off.setAttribute('aria-hidden','false');
    off.classList.add('open');
    if(backdrop) { backdrop.setAttribute('aria-hidden','false'); backdrop.classList.add('visible'); }
    document.documentElement.classList.add('no-scroll');
    if(btn) btn.setAttribute('aria-expanded','true');
    trapFocus(off);
  }

  function closeMenu(){
    if(!off) return;
    off.setAttribute('aria-hidden','true');
    off.classList.remove('open');
    if(backdrop) { backdrop.setAttribute('aria-hidden','true'); backdrop.classList.remove('visible'); }
    document.documentElement.classList.remove('no-scroll');
    if(btn) btn.setAttribute('aria-expanded','false');
    releaseFocus(off);
    if(lastFocused && typeof lastFocused.focus === 'function') lastFocused.focus();
  }

  // event bindings
  if(btn){ btn.addEventListener('click', function(e){
    var expanded = btn.getAttribute('aria-expanded') === 'true';
    if(expanded) closeMenu(); else openMenu();
    e.stopPropagation();
  }); }
  if(closeBtn){ closeBtn.addEventListener('click', function(e){ closeMenu(); e.stopPropagation(); }); }

  // close when clicking backdrop or outside content
  if(backdrop){ backdrop.addEventListener('click', function(){ closeMenu(); }); }
  if(off){ off.addEventListener('click', function(e){ if(e.target===off) closeMenu(); }); }

  // focus sentinels to trap focus robustly
  if(sentinelTop){ sentinelTop.addEventListener('focus', function(){
    // move focus to last focusable inside panel
    var focusables = Array.prototype.slice.call(off.querySelectorAll(focusableSelectors)).filter(function(el){ return off.contains(el); });
    if(focusables.length) focusables[focusables.length-1].focus();
  }); }
  if(sentinelBottom){ sentinelBottom.addEventListener('focus', function(){
    // move focus to first focusable inside panel
    var focusables = Array.prototype.slice.call(off.querySelectorAll(focusableSelectors)).filter(function(el){ return off.contains(el); });
    if(focusables.length) focusables[0].focus();
  }); }

  // close on Escape
  document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeMenu(); });
})();
