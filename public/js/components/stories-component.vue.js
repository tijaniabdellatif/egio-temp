let StoriesComponent = {
    /*html*/
    template: `

    <div :class="stories.length > 0 ? '' : 'd-none'" class="site-section stories-section">

        <div class="stories-container">

            <button class="btn btn-scroll-right">
                <i class="fas fa-angle-right"></i>
            </button>

            <button class="btn btn-scroll-left">
                <i class="fas fa-angle-left"></i>
            </button>

            <div class="stories">

                <template v-for="story in stories">
                    <div class="story-container">
                        <div class="story" @click="selectStory($event,story)">

                            <div class="story-img"
                                :style="{
                                    backgroundImage: 'url(' + story.thumbnail + ')',
                                    borderColor: getUniverColor(story.univer)
                                }">
                            </div>
                            <div class="story-overlay"></div>

                            <div class="story-publisher text-capitalize">
                                <div class="story-publisher-img"
                                    :style="{
                                        borderColor: getUniverColor(story.univer),
                                        backgroundImage: 'url(' + story.publisher.image + ')'
                                    }"></div>
                                <a href="javascript:void(0)" class="story-publisher-name">{{ story.publisher.name }}</a>
                            </div>

                            <div class="story-title">
                                <a href="javascript:void(0)" class="story-title-link">{{ story.title }}</a>
                            </div>

                        </div>
                    </div>
                </template>

            </div>

        </div>

        <template v-if="story && story.images.length > 0 && !isNaN(story.index)">
            <div @click="shownStoryClicked($event)" class="shown-story">

                <div class="shown-story-container">

                    <div class="shown-story-img" :style="{ backgroundImage: 'url(' + story.image + ')' }">
                    </div>

                    <div class="top">
                        <div class="timeline">
                            <div class="timeline-item" v-for="n in story.images.length"
                                @click="goToStoryMedia(n-1)">
                                <template v-if="n < story.index + 1">
                                    <div class="progress" style="width:100%"></div>
                                </template>
                                <template v-if="n == story.index + 1">
                                    <div class="progress" :style="'width:' + story.timeline_progress + '%'" ></div>
                                </template>
                                <template v-else>
                                    <div class="progress" style="width:0%"></div>
                                </template>
                            </div>
                        </div>
                        <div class="shown-story-info">
                            <div class="shown-story-publisher">
                                <div class="story-publisher-img"
                                    :style="{
                                        borderColor: getUniverColor(story.univer),
                                        backgroundImage: 'url(' + story.publisher.image + ')'
                                     }"></div>
                                <div class="story-publisher-name">{{ story.publisher.name }}</div>
                            </div>
                            <div class="controls">
                                <div class="d-flex align-items-center">
                                    <template v-if="story.loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status"
                                            aria-hidden="true"></span>
                                    </template>
                                    <template v-else>
                                        <button class="btn play" @click="play()">
                                            <i class="fas fa-play" v-if="this.story.paused"></i>
                                            <i class="fas fa-pause" v-else></i>
                                        </button>
                                    </template>

                                    <button class="btn btn-exit" @click="exitStory($event)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button
                        class="btn btn-go-to-item"
                        :style="{
                            background: getUniverColor(story.univer)
                        }"
                        @click="toItemById(story.id)">
                        Voir l'annonce
                    </button>

                    <button class="btn btn-left">
                        <i class="fas fa-angle-left"></i>
                    </button>
                    <button class="btn btn-right">
                        <i class="fas fa-angle-right"></i>
                    </button>

                </div>

            </div>
        </template>

    </div>

   `,
    props: {
        multilistType: {
            type: String
        }
    },
    data() {
        return {
            stories: [],
            story: null,
            seens: [
                // {
                //     id : 1,
                //     last_seen_index : 0
                // }
            ]
        }
    },
    watch: {
        // watch stories for changes
        stories: {
            handler(newValue, oldValue) {
                this.setStorieButtonOpacity();
            },
            deep: true
        },
        // watch the seens deeply, any change save it to local storage
        seens: {
            handler(newValue, oldValue) {
                localStorage.setItem('seens', JSON.stringify(newValue));
            },
            deep: true
        }
    },
    computed: {},
    mounted() {
        // load seens from local storage
        let seens = localStorage.getItem('seens');
        if (seens) {
            this.seens = JSON.parse(seens);
        }

        this.loadStories();
        // stories .stories-container
        this.storiesSlider();
    },
    methods: {
        getUniverColor(id) {
            // find the univer with the id in multilistTypeObjs
            let univer = multilistTypeObjs.find(univer => univer.id == id);
            return univer.color;
        },
        loadStories() {
            this.stories = [];

            let paramas = "";

            if (multilistTypeObj.id) {
                paramas += "univer=" + multilistTypeObj.id + "&";
            }

            // add seens to params
            this.seens.forEach(seen => {
                // check if the story is fully seen
                if (seen.last_seen_index == -2){
                    paramas += "seens[]=" + seen.id + "&";
                }
            });

            if (paramas.length > 0) {
                // remove las char
                paramas = paramas.substring(0, paramas.length - 1);
                // add ?
                paramas = "?" + paramas;
            }

            // get /api/v2/getStoriesAds
            axios.get('/api/v2/getStoriesAds' + paramas)
                .then(response => {
                    let data = response.data;

                    if (!data.success) {
                        return;
                    }

                    data = data.data;

                    // loop through the data and add the stories to the stories array
                    for (let i = 0; i < data.length; i++) {
                        let stories = data[i];

                        if (!stories.images || stories.images.length < 1) {
                            continue;
                        }


                        let img_thumbs = stories.images.map(img => img.name);
                        // convert img_thumb ("/images/img_62fd88a5c0cc3.jpg") to img_thumb ("/images/tn_img_62fd88a5c0cc3.jpg")
                        img_thumbs = img_thumbs.map(img => ('/storage' + img).replace(
                            '/storage/images/', '/storage/images/tn_').replace('/storage/old/',
                                '/storage/old/tn_'));


                        // loda very small images ( they are prefexed with vs_ )
                        let img_vs = stories.images.map(img => img.name);
                        img_vs = img_vs.map(img => ('/storage' + img).replace('/storage/images/',
                            '/storage/images/tn_').replace('/storage/old/', '/storage/old/tn_'));

                        // map trough the stories and get just images.name
                        let images = stories.images.map(image => '/storage' + image.name);

                        let publisher_name = stories.username;
                        let publisher_image = stories.avatar;

                        let title = stories.categorie;

                        this.stories.push({
                            thumbnail: img_thumbs[0],
                            images_thumbs: img_thumbs,
                            images_samll: img_vs,
                            images: images,
                            publisher: {
                                image: publisher_image ? '/storage' + publisher_image :
                                    'https://i.pinimg.com/originals/8b/16/7a/8b167af653c2399dd93b952a48740620.jpg',
                                name: publisher_name,
                            },
                            title: title,
                            id: stories.id,
                            univer : stories.parent_cat??null,
                        });
                    }
                    console.log("dd1", data, this.stories);

                    this.setStorieButtonOpacity();

                })
                .catch(error => {
                    console.log(error);
                });

        },
        setStorieButtonOpacity() {

            // get .stories-container
            let storiesContainer = document.querySelector('.stories-container');

            // get .stories
            let stories = storiesContainer?.querySelector('.stories');

            if (!stories) {
                return;
            }

            // get .btn-scroll-right
            let btnScrollRight = storiesContainer.querySelector('.btn-scroll-right');

            // get .btn-scroll-left
            let btnScrollLeft = storiesContainer.querySelector('.btn-scroll-left');


            // get how many pixels to end the scroll
            let initialScrollPosition = stories.scrollWidth - stories.clientWidth;

            // console.log(stories.scrollWidth,stories.clientWidth+stories.scrollLeft);

            // get the opacity based on initialScrollPosition and current scroll position
            let opacity = 1 - (stories.scrollLeft / initialScrollPosition);
            let opacity2 = (stories.scrollLeft / initialScrollPosition);


            // if the opacity is 0 then make the button display none
            if (opacity <= 0) {
                btnScrollRight.style.display = 'none';
            } else {
                btnScrollRight.style.display = 'block';
            }

            if (opacity2 <= 0) {
                btnScrollLeft.style.display = 'none';
            } else {
                btnScrollLeft.style.display = 'block';
            }

            // set the opacity
            btnScrollRight.style.opacity = opacity;
            btnScrollLeft.style.opacity = opacity2;
        },
        storiesSlider() {

            // get .stories-container
            let storiesContainer = document.querySelector('.stories-container');

            // get .stories
            let stories = storiesContainer?.querySelector('.stories');

            if (!stories) {
                return;
            }

            // get .btn-scroll-right
            let btnScrollRight = storiesContainer.querySelector('.btn-scroll-right');

            // get .btn-scroll-left
            let btnScrollLeft = storiesContainer.querySelector('.btn-scroll-left');

            // on click btnScrollRight scroll right
            btnScrollRight.addEventListener('click', () => {
                stories.scrollLeft += stories.clientWidth;
            });

            // on click btnScrollLeft scroll left
            btnScrollLeft.addEventListener('click', () => {
                stories.scrollLeft -= stories.clientWidth;
            });

            this.setStorieButtonOpacity();

            // on scroll .stories
            stories.addEventListener('scroll', this.setStorieButtonOpacity);

            // size change or children change .stories-container
            storiesContainer.addEventListener('DOMSubtreeModified', this.setStorieButtonOpacity);

            // window resize
            window.addEventListener('resize', this.setStorieButtonOpacity);

            // element resizeed
            stories.addEventListener('DOMSubtreeModified', this.setStorieButtonOpacity);

        },
        async goToStoryMedia(index = 0) {

            if (!this.story) return;

            // if its loading user cant do anything
            if (this.story.loading) return;

            // the user saw the story
            this.see(this.story.id, index);

            // remove animejs
            if (this.story?.anime) {
                this.story.anime.pause();
                anime.remove(this.story.anime);
            }

            if (index == 0) {
                this.story.index = 0;
                this.story.timeline_progress = 0;

                this.story.loading = true;

                if (this.story.images_samll[0])
                    this.story.image = await loadImage(this.story.images_samll[0]);
                else
                    this.story.image = null;

                let img = await loadImage(this.story.images[0]);
                this.story.loading = false;

                this.story.image = img;

            } else {
                if (index > this.story.images.length - 1) {
                    this.selectNextStory();
                    return;
                } else if (index < 0) {
                    this.selectPrevStory();
                    return;
                }

                this.story.index = index;
                this.story.timeline_progress = 0;

                this.story.loading = true;

                if (this.story.images_samll[index])
                    this.story.image = await loadImage(this.story.images_samll[index]);
                else
                    this.story.image = null;

                let img = await loadImage(this.story.images[index]);
                this.story.loading = false;

                this.story.image = img;
            }

            // usin anime.js animate the this.story.timeline_progress from 0 to 100 easly
            this.story.anime = anime({
                targets: this.story,
                timeline_progress: [0, 100],
                easing: 'linear',
                duration: this.story.duration,
                update: () => {
                    // console.log(this.story.timeline_progress);
                },
                complete: async () => {
                    // await sleep(500);
                    this.goToStoryMedia(index + 1);
                }
            });

            this.story.paused = this.story.anime.paused;

        },
        async selectStory(e, story, index = 0, animation_rtl = true) {

            // select story if the story has been seen, get the last seen index
            // find the is in seens
            let seen = this.seens.find(s => s.id == story.id);
            if(seen){
                index = seen.last_seen_index;
            }

            await this.exitStory(null, animation_rtl);

            this.story = story;

            this.story.loading = false;

            this.story.keyDown = (e) => {

                // if its loading user cant do anything
                if (this.story.loading) return;

                if (!this.story) return;
                if (e.keyCode == 39) {
                    this.goToStoryMedia(this.story.index + 1);
                } else if (e.keyCode == 37) {
                    this.goToStoryMedia(this.story.index - 1);
                } else if (e.keyCode == 27) {
                    this.exitStory();
                } else if (e.keyCode == 32) {
                    this.play();
                }
            }
            document.addEventListener('keydown', this.story.keyDown);

            let preventClick = false;
            // on click on .shown-story-img
            this.story.click = (e) => {

                // if its loading user cant do anything
                if (this.story.loading) return;

                if (preventClick) {
                    preventClick = false;
                    return;
                }

                let shownImage = document.querySelector('.shown-story-img');

                // create 3 clecked zones 1/3, 2/3 and 3/3
                let clickedZone = e.offsetX / shownImage.offsetWidth;

                if (clickedZone < 0.33) {
                    // go to previous image
                    this.goToStoryMedia(this.story.index - 1);
                } else if (clickedZone > 0.66) {
                    // go to next image
                    this.goToStoryMedia(this.story.index + 1);
                } else {
                    // play/pause
                    this.play();
                }

            };

            // on click next button
            this.story.next = () => {
                // if its loading user cant do anything
                if (this.story.loading) return;

                this.goToStoryMedia(this.story.index + 1);
            }
            // on click previous button
            this.story.previous = () => {
                // if its loading user cant do anything
                if (this.story.loading) return;

                this.goToStoryMedia(this.story.index - 1);
            }

            // add double click event on mobile and desktop
            this.story.doubleClick = (e) => {

                // check if the clicked element is the .shown-story-img
                if (e.target.classList.contains('shown-story-img')) {

                    // get the .shown-story-img
                    let shownImage = document.querySelector('.shown-story-img');

                    // get x and y of the click
                    let x = e.offsetX;
                    let y = e.offsetY;

                    // create heart element
                    let heart = document.createElement('div');
                    heart.classList.add('heart');
                    // add font awesome icon
                    heart.innerHTML = '<i class="fas fa-heart"></i>';
                    // set position to absolute
                    heart.style.position = 'absolute';
                    // set position to x and y of the click
                    heart.style.left = x + 'px';
                    heart.style.top = y + 'px';
                    // set color
                    heart.style.color = '#ff0000';
                    // transform the element to center
                    heart.style.transform = 'translate(-50%, -50%)';
                    // z-index to 100
                    heart.style.zIndex = '100';
                    // font size to 30px
                    heart.style.fontSize = '60px';

                    // add heart to the .shown-story-img
                    shownImage.appendChild(heart);

                    // animate the heart
                    anime({
                        targets: heart,
                        top: '-=50',
                        opacity: [1, 0],
                        easing: 'easeOutQuad',
                        duration: 1000,
                        complete: () => {
                            // remove the heart
                            heart.remove();
                        }
                    });

                }

            }

            // clicks game
            this.story.clicksGame = (e, clicks) => {
                // if its loading user cant do anything
                if (this.story.loading) return;

                let shownImage = document.querySelector('.shown-story-img');

                // create an element to show the clicks
                let clicksElement = document.createElement('div');
                clicksElement.classList.add('clicks-game');
                clicksElement.innerHTML = clicks + " clicks";
                clicksElement.style.position = 'absolute';
                clicksElement.style.left = e.offsetX + 'px';
                clicksElement.style.top = e.offsetY + 'px';
                clicksElement.style.color = '#ff0000';
                clicksElement.style.transform = 'translate(-50%, -50%)';
                clicksElement.style.zIndex = '100';
                clicksElement.style.fontSize = '30px';
                // no wrap
                clicksElement.style.whiteSpace = 'nowrap';

                // add clicksElement to the .shown-story-img
                shownImage.appendChild(clicksElement);

                // animate the clicks
                anime({
                    targets: clicksElement,
                    top: '-=50',
                    opacity: [1, 0],
                    easing: 'easeOutQuad',
                    duration: 1000,
                    complete: () => {
                        // remove the clicks
                        clicksElement.remove();
                        endOfGame(clicks);
                    }
                });

                let endOfGame = (clicks) => {

                    // create multiple hearts based on the clicks, and start animate them from the bottom to the top
                    for (let i = 0; i < clicks; i++) {

                        // get the heighest y position of shownImage
                        let highestY = shownImage.offsetHeight;
                        // get the heighest x position of shownImage
                        let highestX = shownImage.offsetWidth;
                        // random x position between 0 and highestX
                        let x = Math.floor(Math.random() * highestX);
                        // random top animation position between highestY-100 and highestY
                        let top = Math.floor(Math.random() * (highestY - 100)) + highestY;
                        // random duration between 1500 and 2000
                        let duration = Math.floor(Math.random() * 1500) + 1000;

                        let heart = document.createElement('div');
                        heart.classList.add('heart');
                        heart.innerHTML = '<i class="fas fa-heart"></i>';
                        heart.style.position = 'absolute';
                        heart.style.left = x + 'px';
                        heart.style.top = (highestY + 100) + 'px';
                        heart.style.color = '#ff0000';
                        heart.style.transform = 'translate(-50%, -50%)';
                        heart.style.zIndex = '100';
                        heart.style.fontSize = '60px';
                        shownImage.appendChild(heart);

                        anime({
                            targets: heart,
                            top: '-=' + top,
                            opacity: [1, 0],
                            easing: 'easeOutQuad',
                            duration: duration,
                            // random delay between 0 and 1000
                            delay: Math.floor(Math.random() * 1000),
                            complete: () => {
                                // remove the heart
                                heart.remove();
                            }
                        });
                    }



                }
            }

            let down = false;
            let start_x;
            let start_y;
            this.story.down = (e) => {
                // if its loading user cant do anything
                if (this.story.loading) return;

                down = true;

                // get mouse or touch start position
                start_x = e.pageX || e.touches[0].pageX;
                start_y = e.pageY || e.touches[0].pageY;

            }

            // move event
            this.story.move = async (e) => {
                // if its loading user cant do anything
                if (this.story.loading) return;

                if (!down) return;

                preventClick = true;

                // get mouse position
                let x = e.pageX || e.touches[0].pageX;
                let y = e.pageY || e.touches[0].pageY;

                // get the difference between the start position and the current position
                let diff_x = start_x - x;
                let diff_y = start_y - y;


                // if the y diffrence is less than -50px then close the story
                if (diff_y < -100) {
                    await this.exitStory();
                    down = false;
                    return;
                } else if (diff_y > 100) {
                    this.story.doubleClick(e)
                    down = false;
                } else if (diff_x > 100) {
                    await this.selectNextStory();
                    down = false;
                } else if (diff_x < -100) {
                    await this.selectPrevStory();
                    down = false;
                }

            }

            // up event
            this.story.up = () => {
                // if its loading user cant do anything
                if (this.story.loading) return;

                down = false;
            }

            // this function will wait till the .shown-story-img is loaded and then we will do the following
            (async () => {

                // try to get the element
                let shownImage = document.querySelector('.shown-story-img');
                while (!shownImage) {
                    shownImage = document.querySelector('.shown-story-img');
                    await sleep(200);
                }

                // successfully got the element
                let clicks = 0;
                let clicked = false;
                let clickTime = -1;
                let waitingTime = 300; // 500ms
                let clickWaitingTimes = []

                // add click event listener to .shown-story-img
                shownImage.addEventListener('click', async (e) => {
                    clicks++;

                    let clickWaitingTime;

                    if (clickTime == -1)
                        clickWaitingTime = waitingTime;
                    else
                        clickWaitingTime = Date.now() - clickTime;

                    clickTime = Date.now();

                    if (clickWaitingTime < 0)
                        clickWaitingTime = 0;
                    else if (clickWaitingTime > waitingTime)
                        clickWaitingTime = waitingTime;

                    clickWaitingTimes.push(clickWaitingTime);

                    if (clicked) return;

                    clicked = true;
                    for (let i = 0; i < clicks; i++) {
                        await sleep(clickWaitingTimes[i]);
                    }
                    clicked = false;

                    if (clicks == 1)
                        this.story.click(e);

                    if (clicks == 2)
                        this.story.doubleClick(e);

                    if (clicks > 2)
                        this.story.clicksGame(e, clicks);

                    clicks = 0;
                    clickWaitingTimes = [];
                    clickTime = -1;

                });


            })();

            // try to get .shown-story
            (async () => {
                let shownStory = document.querySelector('.shown-story');
                while (!shownStory) {
                    shownStory = document.querySelector('.shown-story');
                    await sleep(200);
                }
                // successfully got the element

                // get .shown-story-container
                let shownStoryContainer = shownStory.querySelector('.shown-story-container');
                // make it display flex
                shownStoryContainer.style.display = 'flex';
                // add animation
                anime({
                    targets: shownStoryContainer,
                    left: [(animation_rtl ? '+' : '-') + '150%', '50%'],
                    easing: 'easeInOutQuad',
                    duration: 500
                });

                // move between story media by clicking the btn-right and btn-left
                // get .btn-right and .btn-left
                let btnRight = shownStory.querySelector('.btn-right');
                let btnLeft = shownStory.querySelector('.btn-left');

                // add event listener to .btn-right and .btn-left
                btnRight.addEventListener('click', this.story.next);
                btnLeft.addEventListener('click', this.story.previous);

                let shownImage = document.querySelector('.shown-story-img');
                shownImage.removeEventListener('click', this.story.click);
                // add touch and mouse down/move/up events to .shown-story-img
                shownImage.addEventListener('mousedown', this.story.down);
                shownImage.addEventListener('touchstart', this.story.down);
                shownImage.addEventListener('mousemove', this.story.move);
                shownImage.addEventListener('touchmove', this.story.move);
                shownImage.addEventListener('mouseup', this.story.up);
                shownImage.addEventListener('touchend', this.story.up);

            })();

            // if user navigate back, don't go back either exit the story
            // window.onpopstate = () => {
            //     this.exitStory();
            // }

            this.story.timeline_progress = 0;

            this.story.duration = 5000; // 5sec

            this.goToStoryMedia(index);

        },
        async exitStory(e, animation_rtl = true) {

            if (!this.story) {
                return;
            }

            // storp anime js
            if (this.story?.anime) {
                this.story.anime.pause();
                anime.remove(this.story.anime);
            }

            // remove this.story.keyDown from event listener
            document.removeEventListener('keydown', this.story.keyDown);

            // remove .btn-right and .btn-left event listener
            let shownStory = document.querySelector('.shown-story');
            let btnRight = shownStory.querySelector('.btn-right');
            let btnLeft = shownStory.querySelector('.btn-left');
            btnRight.removeEventListener('click', this.story.next);
            btnLeft.removeEventListener('click', this.story.previous);

            // remove .shown-story-img click event listener
            let shownImage = document.querySelector('.shown-story-img');
            shownImage.removeEventListener('click', this.story.click);

            shownImage.removeEventListener('mousedown', this.story.down);
            shownImage.removeEventListener('touchstart', this.story.down);
            shownImage.removeEventListener('mousemove', this.story.move);
            shownImage.removeEventListener('touchmove', this.story.move);
            shownImage.removeEventListener('mouseup', this.story.up);
            shownImage.removeEventListener('touchend', this.story.up);

            // add animation
            let shownStoryContainer = shownStory.querySelector('.shown-story-container');
            await (async () => new Promise((resolve, reject) => {
                anime({
                    targets: shownStoryContainer,
                    left: ['50%', (animation_rtl ? '-' : '+') + '150%'],
                    easing: 'easeInOutQuad',
                    duration: 500,
                    complete: () => {
                        // male shownStoryContainer display none
                        shownStoryContainer.style.display = 'none';
                        resolve();
                    },
                    error: (err) => {
                        reject(err);
                    }
                });
            }))();

            // clear storyInterval
            this.story = null;

        },
        async goToStory(story_index, media_index = 0, animation_rtl = true) {

            let story = this.stories[story_index];

            if (story == this.story) {
                return;
            }

            await this.exitStory();
            if (story) {
                await this.selectStory(null, story, media_index, animation_rtl);
            }
        },
        async selectNextStory() {
            let index = this.stories.indexOf(this.story) + 1;
            if (index > this.story.images.length | index < 0) {
                await this.exitStory();
            } else {
                await this.goToStory(index);
            }
        },
        async selectPrevStory() {
            let index = this.stories.indexOf(this.story) - 1;
            if (index > this.story.images.length - 1 | index < 0) {
                await this.exitStory(null, false);
            } else {
                await this.goToStory(index, this.stories[index].images.length - 1, false);
            }
        },
        play() {
            // check if animejs (this.story.anime) is playing
            if (this.story.anime.paused) {
                this.story.anime.play();
            } else {
                this.story.anime.pause();
            }

            this.story.paused = this.story.anime.paused;
        },
        changeStoryElementStyleBazedOnSize() {
            let storiesContainer1 = document.querySelector('.stories-container');

            let calculate = () => {
                // get .stories
                let storiesContainer2 = storiesContainer1.querySelector('.stories');

                // get .story-container
                let stories = storiesContainer2.querySelectorAll('.story-container');


            }
        },
        shownStoryClicked(e) {
            // check if .shown-story clicked exacly and not one of its children
            if (e.target.classList.contains('shown-story')) {
                this.exitStory();
            }
        },
        isStoryHasBeenSeen(id, index = -1) {
            // find story id in seens
            let seen = this.seens.find((story) => story.id == id);
            // find story
            let story = this.stories.find((story) => story.id == id);

            // if seen or story not found return false
            if (!seen || !story) {
                return false;
            }

            // if index is -1, check if all story media has been seen
            if (index == -1) {
                // get the last seen index
                let last_seen_index = seen.last_seen_index ?? -1;

                // if last_seen_index is equal to story media length, return true
                if (last_seen_index + 1 == story.images.length) {
                    return true;
                }
            }
            else {
                // if index is not -1, check if index has been seen
                if (seen.last_seen_index >= index) {
                    return true;
                }
            }

        },
        see(id, index = -1) {
            // find story id in seens
            let seen = this.seens.find((story) => story.id == id);

            // check if all story media has been seens
            if (this.isStoryHasBeenSeen(id))
                index = -2;

            // if story is not found in seens, add it
            if (!seen) {
                this.seens.push({
                    id: id,
                    last_seen_index: index
                });
            }
            else {
                // if story is found in seens, update last_seen_index
                seen.last_seen_index = index;
            }
        },
        toItemById(id) {
            window.location.href = '/item/' + id;
        }
    },
};
