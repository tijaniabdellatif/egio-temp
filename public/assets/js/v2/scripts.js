// on scroll header
window.onscroll = function () {
    var header = document.querySelector(".header");

    // get header scroll position
    var scrollPosition = window.pageYOffset;
    if (scrollPosition > 0) {
        header?.classList.add("scrolled");
    } else {
        header?.classList.remove("scrolled");
    }

}

// place holder text animation for inputs
function animatePlaceholder(input, placeholders = []) {

    const nexttext = () => {
        pos++;

        if (pos >= placeholders.length) {
            pos = 0;
        }

        text = placeholders[pos];

        animateText(input, text, () => {
            setTimeout(nexttext, 200);
        });
    };

    const animateText = function (input, text, endCallback) {
        let i = 0;
        let animationInterval = setInterval(() => {

            if (i % 14 > 7) {
                pointer = "";
            } else {
                pointer = "|";
            }

            let subText = text.substring(0, i) + pointer;

            // set subText to input placeholder
            input.setAttribute("placeholder", subText);

            if (i == text.length) {
                setTimeout(() => {

                    clearInterval(animationInterval);

                    // remove text from input placeholder
                    let removeTextInterval = setInterval(() => {
                        subText = subText.substring(0, subText.length -
                            1);
                        input.setAttribute("placeholder", subText);

                        if (subText.length == 0) {
                            clearInterval(removeTextInterval);
                            if (endCallback)
                                endCallback();
                        }
                    }, 50);

                }, 4000);
            }

            i++;

        }, 50);
    }

    if (placeholders.length == 0) {
        placeholders = [
            "Enter your text here"
        ];
    }

    let pos = 0;

    let text = placeholders[pos];

    animateText(input, text, () => {
        setTimeout(nexttext, 200);
    });

}

async function asyncQuerySelectorAll(selector, parent = document, interval = 500, until = 60000) {
    return new Promise(async (resolve, reject) => {
        let i = 0;
        while (true) {
            let element = parent.querySelectorAll(selector);

            if (element) {
                resolve(element);
                break;
            }

            if (i * interval > until) {
                reject(new Error("Element not found"));
                break;
            }

            await sleep(interval);
        }
    });
}

async function asyncQuerySelector(selector, parent = document, interval = 500, until = 60000) {
    let out = await asyncQuerySelectorAll(selector, parent, interval, until);
    return out[0] ?? null;
}

async function slide(sildeId, waitTime = 5000, animateTime = 500) {

    // let slide = document.getElementById(sildeId);
    let slide = await asyncQuerySelector(`#${sildeId}`);

    if (!slide) {
        return;
    }

    // get slide items
    // let slideItems = slide.querySelectorAll(".slide-item");
    let slideItems = await asyncQuerySelectorAll(`.slide-item`, slide);

    //
    if(slideItems.length == 0)
        return;

    // get item width and height
    let itemWidth = slideItems[0].offsetWidth;
    let itemHeight = slideItems[0].offsetHeight;

    // set the height to the slide
    slide.style.height = itemHeight + "px";

    // how many items can the slide display in the screen
    let itemsInScreen = Math.floor(slide.offsetWidth / itemWidth);


    let rtlSlide = true;

    // set items positions
    let itemsOrder = [];
    for (let i = 0; i < slideItems.length; i++) {
        itemsOrder.push(i);
    }

    let lockAnimation = false;
    let moveItemsToTherePosition = function (useAnimation = true) {

        if (lockAnimation) {
            return;
        }

        lockAnimation = true;
        for (let i = 0; i < slideItems.length; i++) {
            if (useAnimation && anime) {

                if (rtlSlide && itemsOrder[i] == slideItems.length - 1) {

                    // create a copy of the current slide
                    let copySlideItem = slideItems[i].cloneNode(true);
                    copySlideItem.classList.add('copy-slide-item');
                    slide.appendChild(copySlideItem);

                    // animate the copy
                    anime({
                        targets: copySlideItem,
                        left: (itemsOrder[(i + 1) % slideItems.length] - 1) * itemWidth,
                        duration: animateTime,
                        easing: 'easeInOutQuad',
                        complete: () => {
                            // remove the copy from the slide
                            copySlideItem.remove();
                        }
                    });

                    slideItems[i].style.left = (itemsOrder[i] + 1) * itemWidth + "px";
                    anime({
                        targets: slideItems[i],
                        left: itemsOrder[i] * itemWidth,
                        duration: animateTime,
                        easing: 'easeInOutQuad',
                    });

                }
                else if (!rtlSlide && itemsOrder[i] == 0) {

                    // create a copy of the current slide
                    let copySlideItem = slideItems[i].cloneNode(true);
                    copySlideItem.classList.add('copy-slide-item');
                    slide.appendChild(copySlideItem);

                    // animate the copy
                    anime({
                        targets: copySlideItem,
                        left: (itemsOrder[i - 1 < 0 ? slideItems.length - 1 : i - 1] + 1) * itemWidth,
                        duration: animateTime,
                        easing: 'easeInOutQuad',
                        complete: () => {
                            // remove the copy from the slide
                            copySlideItem.remove();
                        }
                    });

                    slideItems[i].style.left = (itemsOrder[i] - 1) * itemWidth + "px";
                    anime({
                        targets: slideItems[i],
                        left: itemsOrder[i] * itemWidth,
                        duration: animateTime,
                        easing: 'easeInOutQuad',
                        complete: () => {
                            // remove the copy from the slide
                            copySlideItem.remove();
                        }
                    });

                }
                else {

                    anime({
                        targets: slideItems[i],
                        left: itemsOrder[i] * itemWidth + "px",
                        duration: animateTime,
                        easing: "easeInOutQuad",
                        complete: () => {
                            lockAnimation = false;
                        }
                    });

                }

            } else {
                slideItems[i].style.left = itemsOrder[i] * itemWidth + "px";
                lockAnimation = false;
            }
        }

    }

    let autoMove = () => {
        setTimeout(() => {
            if (rtlSlide)
                moveBtnPrevClick();
            else
                moveBtnNextClick();
            autoMove();
        }, waitTime);
    }

    moveItemsToTherePosition(false);
    autoMove();

    // get mov-btn-next mov-btn-prev
    let movBtnNext = slide.querySelector(".mov-btn-next");
    let movBtnPrev = slide.querySelector(".mov-btn-prev");

    let moveBtnNextClick = () => {
        if (lockAnimation)
            return;
        if (slideItems.length * itemWidth < slide.offsetWidth) {
            return;
        }
        rtlSlide = false;
        itemsOrder.push(itemsOrder.shift());
        moveItemsToTherePosition();
    };

    let moveBtnPrevClick = () => {
        if (lockAnimation)
            return;
        if (slideItems.length * itemWidth < slide.offsetWidth) {
            return;
        }
        rtlSlide = true;
        itemsOrder.unshift(itemsOrder.pop());
        moveItemsToTherePosition();
    };

    // on click mov-btn-next
    movBtnNext.addEventListener("click", moveBtnNextClick);

    // on click mov-btn-prev
    movBtnPrev.addEventListener("click", moveBtnPrevClick);

    let mouseEventNames = {
        down: ['touchstart', 'mousedown'],
        move: ['touchmove', 'mousemove'],
        up: ['touchend', 'mouseup']
    };

    mouseEventNames.down.forEach((eventName, i) => {

        // on mouse down and move to position
        slide.addEventListener(mouseEventNames.down[i], (e) => {

            // get X position of mouse or touch
            let mouseX = e.clientX || e.touches[0].clientX;

            let moveSlideBasedOnMousePosition = (e) => {

                // check if mouse or touch is still down
                if (!e.buttons && !e.touches) {
                    slide.removeEventListener(mouseEventNames.move[i], moveSlideBasedOnMousePosition);
                    slide.style.cursor = "auto";
                    return;
                }

                // get X position of mouse
                let mouseXNew = e.clientX || e.touches[0].clientX;

                // get the difference between the two positions
                let diff = mouseXNew - mouseX;

                if (Math.abs(diff) < 100) return;

                // if the difference is less than zero then we are moving to the left
                if (diff < 0) {
                    // click mov-btn-prev
                    moveBtnPrevClick();
                }
                // if the difference is greater than zero then we are moving to the right
                else if (diff > 0) {
                    // click mov-btn-next
                    moveBtnNextClick();
                }

                // remove the current event handler
                slide.removeEventListener(mouseEventNames.move[i], moveSlideBasedOnMousePosition);

                // enable events on slide and its childs
                slide.style.pointerEvents = "auto";
                slide.querySelectorAll("*").forEach(item => {
                    item.style.pointerEvents = "auto";
                });

            };

            // add event mouse move
            slide.addEventListener(mouseEventNames.move[i], moveSlideBasedOnMousePosition);

        });

    });


}

// numbers
let numbers = [];
let hidephone = () => {

    // get all .hidephone
    let hidephones = document.querySelectorAll(".hidephone");


    // loop through all phones
    hidephones.forEach(phone => {

        // get .phone
        let phoneElement = phone.querySelector(".phone");

        // save the phone number
        numbers.push({
            number: phoneElement.innerHTML,
            element: phoneElement
        });

        let nbrCharShown = 6;

        // create regex to match /(\d{6})(\d*)/
        let regex = new RegExp(`(\\d{${nbrCharShown}})(\\d*)`);

        // show just the 5 caracter and replace others with x
        phoneElement.innerHTML = phoneElement.innerHTML.replace(regex, '$1' + 'x'.repeat(phoneElement.innerHTML.length - nbrCharShown));

        // add onclick event
        phone.addEventListener("click", (e) => {

            let clikedElement = e.target;

            // check if the clicked element is a phone number or contains element with class phone
            let phoneElement = clikedElement;

            if (clikedElement.classList.contains("phone")) {
                phoneElement = clikedElement;
            }
            else if (clikedElement.classList.contains("hidephone")) {
                phoneElement = clikedElement.querySelector(".phone");
            }

            if (phoneElement) {

                // get the phone element

                // get the phone number from numbers
                let phoneNumber = numbers.find(item => item.element == phoneElement).number;

                // show the phone number
                phoneElement.innerHTML = phoneNumber;

                // display the phone number after confirm dialog
                window.location.href = "tel:" + phoneNumber;

            }
        });

    });

}

let showMore = [];
seeMore = (elem) => {
    console.log(elem);
    // get the inner text of the element
    let innerText = elem.innerText;
    console.log(innerText);

    // create see more link
    let seeMoreLink = document.createElement("a");
    seeMoreLink.innerHTML = " see more";
    seeMoreLink.href = "#seemore";
    seeMoreLink.classList.add("see-more");

    // add onclick event
    seeMoreLink.addEventListener("click", (e) => {

        // get the clicked element
        let clickedElement = e.target;

        console.log(clickedElement);

        // get the parent element
        let parentElement = clickedElement.parentElement;

        // find the parent element in showMore
        let parentElementIndex = showMore.findIndex(item => {
            return item.element == parentElement;
        });

        if (parentElementIndex == -1)
            return;

        // get the inner html of the parent element
        let innerText = showMore[parentElementIndex].innerText;

        // set the inner html of the parent element
        parentElement.innerText = innerText;

        // remove the see more link
        seeMoreLink.remove();

        // add see less link
        let seeLessLink = document.createElement("a");
        seeLessLink.innerHTML = " see less";
        seeLessLink.href = "#seeless";
        seeLessLink.classList.add("see-less");

        // add onclick event
        seeLessLink.addEventListener("click", (e) => {

            // get the clicked element
            let clickedElement = e.target;

            // get the parent element
            let parentElement = clickedElement.parentElement;

            // call the seeMore function
            seeMore(parentElement);

            // remove the see less link
            seeLessLink.remove();

        });

        // add see less link
        parentElement.appendChild(seeLessLink);

    });

    // save the inner text of the element
    showMore.push({
        innerText: innerText,
        element: elem
    });

    // display the first 30 words of the inner html
    elem.innerText = innerText.split(" ").slice(0, 30).join(" ") + "... ";

    // add the see more link
    elem.appendChild(seeMoreLink);

}

function multilistPopup() {

    // get all .multilist-popup
    let multilistPopups = document.querySelectorAll(".multilist-popup");

    // loop through themes
    multilistPopups.forEach((multilistPopup) => {

        // on click .multilist-popup-gray-bg
        multilistPopup.querySelector(".multilist-popup-gray-bg").addEventListener("click", (e) => {
            close(multilistPopup);
        });

        // get .close-btn and add event click
        multilistPopup.querySelector(".close-btn").addEventListener("click", (e) => {
            close(multilistPopup);
        });

    });

    let close = (multilistPopup) => {
        multilistPopup.classList.remove("active");
        document.querySelector("body").classList.remove("modal-open");
    }

    let open = (multilistPopup) => {
        multilistPopup.classList.add("active");
    }

}

// convert obj to url params
function objToUrlParams(obj) {
    let toUrlParams = (obj, prefex = '') => {

        // create url params
        let urlParams = "";

        // loop through obj
        for (let key in obj) {
            let val = obj[key];

            if (val == null) continue;
            if (val == undefined) continue;
            // if(val == '') continue;

            // if val is an object then call toUrlParams
            if (val instanceof Array) {
                // convert val from Array to object
                let valToObj = {};
                val.forEach((v, i) => {
                    valToObj[i] = v;
                });

                val = valToObj;
            }

            let newPrefex = prefex + key;

            if (val instanceof Object) {
                urlParams += toUrlParams(val, newPrefex + '-');
            }
            else {
                urlParams += newPrefex + '=' + val;
            }

            urlParams += '&';
        }

        // remove last &
        urlParams = urlParams.slice(0, -1);

        // return url params
        return urlParams;

    }

    // encodeURI
    return encodeURI(toUrlParams(obj));
}

// convert url params to obj
function urlParamsToObj(urlParams) {

    // decodeURI
    urlParams = decodeURI(urlParams);

    let toObj = (urlParams) => {

        let obj = {};

        let urlParamsArr = urlParams.split('&');

        let subUrlParramsObj = {};

        // loop through urlParams
        for (let i = 0; i < urlParamsArr.length; i++) {
            let item = urlParamsArr[i];

            // get key and value
            let key = item.split('=')[0];
            let val = item.split('=')[1] ?? null;
            let keys = key.split('-');

            if (val == "null") {
                val = null;
            }
            else if (val == "undefined") {
                val = undefined;
            }
            else if (val == "true") {
                val = true;
            }
            else if (val == "false") {
                val = false;
            }
            else if (val == "NaN") {
                val = NaN;
            }
            else if (val == "Infinity") {
                val = Infinity;
            }

            // if keys length is 1 then set obj[key] to val
            if (keys.length == 1) {
                // check if obj contains key
                if (obj.hasOwnProperty(key)) {
                    // if obj[key] is an array then push val
                    if (obj[key] instanceof Array) {
                        obj[key].push(val);
                    } else {
                        // create array and push val
                        obj[key] = [obj[key], val];
                    }
                }
                else {
                    obj[key] = val;
                }
            }
            // if keys length is 2 then set obj[keys[0]][keys[1]] to val
            else if (keys.length > 1) {

                let key0 = keys[0];

                // check if subUrlParramsObj contains keys[0]
                if (!subUrlParramsObj[key0]) {
                    subUrlParramsObj[key0] = [];
                }

                // remove keys[0] from keys
                keys.shift();
                // join keys with -
                key = keys.join('-');

                let param = key + '=' + val;

                // add param to subUrlParramsObj[keys[0]]
                subUrlParramsObj[key0].push(param);

            }

        }

        // loop through subUrlParramsObj
        for (let key in subUrlParramsObj) {

            // join subUrlParramsObj[key] with &
            let val = subUrlParramsObj[key].join('&');

            // set obj[key] to val
            obj[key] = toObj(val);

        }

        // return obj
        return obj;

    }

    return checkIfObjShouldBeArrayAndConvert(toObj(urlParams));

}

// check if object should be converted to array, if its keys are numbers
function checkIfObjShouldBeArrayAndConvert(obj) {

    // if obj is an array
    if (obj instanceof Array) {
        // loop through obj
        obj.forEach((item, i) => {
            // check if item is an object
            if (item instanceof Object) {
                // convert item to array
                obj[i] = checkIfObjShouldBeArrayAndConvert(item);
            }
        });

        // return obj
        return obj;
    }

    // check if all keys are numbers
    let canConvertToArray = true;
    for (let key in obj) {

        // get value
        let val = obj[key];

        // check if value is an object or Array
        if (val instanceof Object || val instanceof Array) {
            obj[key] = checkIfObjShouldBeArrayAndConvert(val);
        }

        if (isNaN(key)) {
            canConvertToArray = false;
        }
    }

    // order obj by keys
    let orderedObj = {};
    Object.keys(obj).sort().forEach(function (key) {
        orderedObj[key] = obj[key];
    });

    // check if the first key is 0
    if (Object.keys(orderedObj)[0] != 0) {
        canConvertToArray = false;
    }

    // check if the keys step is 1
    let keys = Object.keys(orderedObj);
    // loop through keys
    for (let i = 0; i < keys.length - 1; i++) {
        // get key
        let key = keys[i];
        // get next key
        let nextKey = keys[i + 1];
        // get key step
        let keyStep = nextKey - key;
        // check if key step is 1
        if (keyStep != 1) {
            canConvertToArray = false;
            break;
        }
    }

    // if all keys are numbers then convert obj to array
    if (canConvertToArray) {
        let arr = [];
        for (let key in orderedObj) {
            arr.push(orderedObj[key]);
        }
        return arr;
    }

    // return obj
    return obj;
}

// add params to url
function addParamsToUrl(params, url = window.location.href) {

    // check if url has params
    if (url.indexOf('?') == -1) {
        url += '?';
    }
    else {
        url += '&';
    }

    return url + params ?? '';
}

function addObjToUrl(obj, url = window.location.href) {
    return addParamsToUrl(objToUrlParams(obj), url);
}

// extract params from url
function extractParamsFromUrl(url = window.location.href) {
    return urlParamsToObj(url.split('?')[1]);
}

// document ready
document.addEventListener('DOMContentLoaded', function () {
    // check if url has parameter hacked
    let hacked = extractParamsFromUrl().hacked;

    if (hacked) {
        // get url without hacked
        let url = window.location.href;
        // remove hacked with its value using regex replace
        url = url.replace(/\?hacked(=)*[^&]*/g, '');
        // redirect to url without hacked

        // replace url without redirecte
        window.history.replaceState(null, null, url);

        document.querySelectorAll("*").forEach((item) => {
            // set random color and background color
            item.style.color = `rgba(${Math.floor(Math.random() * 255)},${Math.floor(Math.random() * 255)},${Math.floor(Math.random() * 255)})`;

            // get computed style
            let computedStyle = window.getComputedStyle(item);

            // check item has background color
            if (computedStyle.getPropertyValue('background-color') != 'rgba(0, 0, 0, 0)' || hacked == 'all') {
                item.style.backgroundColor = `rgba(${Math.floor(Math.random() * 255)},${Math.floor(Math.random() * 255)},${Math.floor(Math.random() * 255)})`;
            }
        });

    }
});

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// load file
let file_cache = {};
function loadFile(url) {
    return new Promise((resolve, reject) => {
        // check if cache contains url
        if (file_cache[url]) {
            // return cache
            resolve(file_cache[url]);
            return;
        }

        let xhr = new XMLHttpRequest()
        xhr.open('GET', url)
        xhr.responseType = 'arraybuffer'
        xhr.onload = (e) => {
            // put response in cache
            file_cache[url] = xhr.response;
            // check how many keys in cache
            if (Object.keys(file_cache).length > 100) {
                delete file_cache[Object.keys(file_cache)[0]];
            }
            // create url from arraybuffer
            resolve(xhr.response)
        }
        xhr.onerror = reject
        xhr.send()
    })
}

// load image
async function loadImage(src) {
    let arraybuffer = await loadFile(src);
    let blob = new Blob([arraybuffer]);
    let url = URL.createObjectURL(blob);
    return url;
}
