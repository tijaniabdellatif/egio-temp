let uploadFilesComponent = {
    /*html*/
    template: `
        <div class="upload-container" >
            <!-- modal to display the selected file -->
            <div class="modal fade" v-if="modalFile" :id="'file-modal-'+id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Files :</h5>
                            <div class="nav-file" >
                                <button type="button" class="btn text-primary btn-sm" @click="prevFile">
                                    <i class="fa fa-chevron-left"></i>
                                </button>
                                <span class="mx-2 text-primary" >
                                    {{ modalFile.current }} / {{ modalFile.total }}
                                </span>
                                <button type="button" class="btn text-primary btn-sm" @click="nextFile">
                                    <i class="fa fa-chevron-right"></i>
                                </button>
                            </div>
                            <button type="button" class="close btn p-0 m-0 text-danger" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times" data-dismiss="modal" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex align-items-center justify-content-center" >
                                <template v-if="type == 'images'" >
                                    <img :src="modalFile.src" class="img-fluid">
                                </template>
                                <template v-if="type == 'audios'" >
                                    <audio controls>
                                        <source :src="modalFile.src" type="audio/mpeg" :key="modalFile.src">
                                        Your browser does not support the audio element.
                                    </audio>
                                </template>
                                <template v-else-if="type == 'videos'" >
                                    <video controls="" class="mw-100" :alt="modalFile.src" :key="modalFile.src">
                                        <source :src="modalFile.src" type="video/mp4">
                                    </video>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="file" :id="'upload-files-input-'+id" :class="error?'is-invalid':''" @change="selectedFiles"
            class="form-control d-none" multiple="multiple">
            <div class="upload-files" :class="error?'is-invalid':''" @drop.stop="onDropFile($event)" @paste="onPasteFile($event)" @dragenter.prevent @dragover.prevent @click.stop="clickUploadFiles('#upload-files-input-'+id)">
                <template v-if="uploading">
                    <div class="upload-files-progress" :style="'width:'+uploadProgress+'%'"></div>
                    <span v-if="files.length != 0" class="upload-progress-text">{{ uploadProgress }}%</span>
                </template>
                <div v-for="(file,index) in files" class="file-item">
                    <div @drop.stop="onDrop($event,index)" @dragenter.prevent @dragover.prevent>

                        <template  v-if="type == 'images'" >
                            <div class="img-thumbnail" @click.stop="onClickFile($event,index)" draggable="true" @dragstart="startDrag($event,index)"
                                :style="'background-image: url(' + getThumbnail('/storage' + file?.name) + ')'">
                                <button @click.stop="removeFiles(file)" class="btn-remove">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </template>
                        <template v-else-if="type == 'videos'">
                            <div class="img-thumbnail" @click.stop="onClickFile($event,index)" draggable="true" @dragstart="startDrag($event,index)"
                            :style="'background-image: url(' + getThumbnail('/storage' + file?.name) + ')'">
                                <div class="">
                                    <i class="fas fa-video"></i>
                                </div>
                                <button @click.stop="removeFiles(file)" class="btn-remove">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </template>
                        <template v-else-if="type == 'audios'">
                            <div class="img-thumbnail" @click.stop="onClickFile($event,index)" draggable="true" @dragstart="startDrag($event,index)"
                            :style="'background: white'">
                                <div class="">
                                    <i class="fas fa-music"></i>
                                </div>
                                <button @click.stop="removeFiles(file)" class="btn-remove">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </template>
                        <template v-else>
                            <div class="file-icon d-flex items-align-center justify-content-center" @click.stop="onClickFile($event,index)" draggable="true" @dragstart="startDrag($event,index)">
                                <div class="">
                                    <i class="fa fa-file-alt"></i>
                                </div>
                                <button @click.stop="removeFiles(file)" class="btn-remove">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </template>

                    </div>
                </div>
                <div v-if="files.length == 0" class="text-center w-100 progress-placeholder">
                    <template v-if="uploading" >
                        <span class="me-2">{{ uploadProgress }}%</span>
                    </template>
                    <template v-else>
                        <span class="me-2">{{ getPlaceholder }}</span>
                        <i class="fa fa-upload" aria-hidden="true"></i>
                    </template>
                </div>
            </div>
        </div>
    `,
    props: {
        files: {
            type: Array,
            default: () => []
        },
        max: {
            type: Number,
            default: 5
        },
        multiple: {
            type: Boolean,
            default: false
        },
        type: {
            type: String,
            default: 'images'
        },
        placeholder: {
            type: String,
            default: ""
        },
        allowedExtensions: {
            type: Array,
            default: () => ['*']
        },
        error: {
            type: Boolean,
            default: false
        }
    },
    emits: ['update:files'],
    data() {
        return {
            filesWhoHasThumb: ['png', 'jpg', 'jpeg', 'webp'],
            uploadProgress: 0,
            uploading: false,
            selectedFile: null,
            id: null
        }
    },
    computed: {
        modalFile: function () {
            if (!this.selectedFile) {
                return {};
            }

            if (this.type == 'images') {
                let src = '/storage' + this.selectedFile?.name;
                return {
                    backgroundImage: 'url(/storage/images/' + this.selectedFile.name + ')',
                    index: this.files.indexOf(this.selectedFile),
                    current: this.files.indexOf(this.selectedFile) + 1,
                    total: this.files.length,
                    src: src,
                    thumb: this.getThumbnail(src),
                    type: 'image'
                };
            }
            else if (this.type == 'videos') {
                let src = '/storage' + this.selectedFile?.name;

                return {
                    src: src,
                    index: this.files.indexOf(this.selectedFile),
                    current: this.files.indexOf(this.selectedFile) + 1,
                    total: this.files.length,
                    type: 'video'
                };
            }
            else if (this.type == 'audios') {
                let src = '/storage' + this.selectedFile?.name;

                return {
                    src: src,
                    index: this.files.indexOf(this.selectedFile),
                    current: this.files.indexOf(this.selectedFile) + 1,
                    total: this.files.length,
                    type: 'audio'
                };
            }

            // rest of types
            return {
                index: this.files.indexOf(this.selectedFile),
                current: this.files.indexOf(this.selectedFile) + 1,
                total: this.files.length,
                src: '/storage' + this.selectedFile?.name,
                type: 'file'
            };
        },
        getPlaceholder: function () {

            if (this.placeholder.length > 0)
                return this.placeholder;

            if (this.type == 'images') {
                if (this.multiple)
                    return 'Drop images here or click to upload';
                else
                    return 'Drop image here or click to upload';
            }
            else if (this.type == 'videos') {
                if (this.multiple)
                    return 'Drop videos here or click to upload';
                else
                    return 'Drop video here or click to upload';
            }
            else if (this.type == 'audios') {
                if (this.multiple)
                    return 'Drop audios here or click to upload';
                else
                    return 'Drop audio here or click to upload';
            }
            else {
                if (this.multiple)
                    return 'Drop files here or click to upload';
                else
                    return 'Drop file here or click to upload';
            }

            return 'Upload files'
        }
    },
    watch: {},
    mounted() {

        // generate id for curent component
        this.id = Math.random().toString(36).substr(2, 9);

        setTimeout(() => {
            // if (this.multiple) {
            //     $('#upload-files-input-' + this.id).attr('multiple', true);
            // }

            // set allowed extesions to the input element
            if (this.allowedExtensions.indexOf('*') == -1) {
                $('#upload-files-input-' + this.id).attr('accept', this.allowedExtensions.map((ext) => '.' + ext).join(','));
            }

        }, 1000);

    },
    methods: {
        selectedFiles(e) {
            let files = e.target.files;
            this.uploadFiles(files);
        },
        removeFiles(file) {
            let files = this.files.filter(i => i != file);
            this.$emit('update:files', files);
        },
        clickUploadFiles(id) {
            $(id).click();
        },
        onDropFile(e) {
            e.preventDefault();

            let files = e.dataTransfer.files;
            this.uploadFiles(files);
        },
        onPasteFile(e) {
            e.preventDefault();
            let files = e.clipboardData.files;
            this.uploadFiles(files);
        },
        startDrag(e, index) {
            console.log("startDrag", index)
            e.dataTransfer.dropEffect = "move";
            e.dataTransfer.effectAllowed = "move";
            e.dataTransfer.setData("file_index", index);
        },
        onDrop(e, to) {
            // change the file position to the new position
            console.log("onDrop", to);
            let index = e.dataTransfer.getData("file_index");
            let files = [...this.files];
            let file = files[index];
            files.splice(index, 1);
            files.splice(to, 0, file);
            this.$emit('update:files', files);
        },
        uploadFiles(files) {

            if (this.uploading) {
                return;
            }

            let allowedFiles = [];

            if (this.allowedExtensions.indexOf("*") == -1) {
                // check if the file is allowed to be uploaded
                let notAllowedFiles = [];
                for (let i = 0; i < files.length; i++) {
                    let file = files[i];

                    let extension = file.name.toLowerCase().split('.').pop();
                    if (this.allowedExtensions.indexOf(extension) != -1) {
                        allowedFiles.push(file);
                    }

                    console.log(allowedFiles);
                }

                if (allowedFiles.length != files.length) {
                    alert("You can only upload files with the following extensions: " + this.allowedExtensions.join(', '));
                }
            }
            else {
                allowedFiles = files;
            }

            if (allowedFiles.length == 0) {
                return;
            }

            let max = this.max;

            if (!this.multiple) {
                allowedFiles = [allowedFiles[0]];
                max = 1;
                if (this.files.length > 0) {
                    this.$emit('update:files', []);
                    // sleep for 1 second
                    setTimeout(() => {
                        this.uploadFiles(allowedFiles);
                    }, 100);
                    return;
                }
                console.log(this.files.length);
            }

            if (this.files.length + allowedFiles.length > max) {

                if (max == 1) {
                    alert("You can only upload 1 file");
                } else {
                    alert("You can only upload " + max + " files");
                }

                return;
            }

            if (this.type == "images") {
                this.uploading = true;
                uploadImages(allowedFiles,
                    (data) => {
                        this.uploading = false;
                        this.uploadProgress = 0;
                        if (data.success) {
                            // add images to existing images
                            let images = this.files.concat(data.images);
                            this.$emit('update:files', images);
                        }
                        else {
                            alert(data);
                        }
                    },
                    (progress) => {
                        this.uploadProgress = progress;
                    });
            }
            else if (this.type == "videos") {
                this.uploading = true;
                uploadVideos(allowedFiles,
                    (data) => {
                        console.log(data)
                        this.uploading = false;
                        this.uploadProgress = 0;
                        if (data.success) {
                            // add the new videos into the existing video array
                            let videos = this.files.concat(data.videos);
                            this.$emit('update:files', videos);
                        }
                        else {
                            alert(data);
                        }
                    },
                    (progress) => {
                        this.uploadProgress = progress;
                    });
            }
            else if (this.type == "audios") {
                this.uploading = true;
                uploadAudios(allowedFiles,
                    (data) => {
                        this.uploading = false;
                        this.uploadProgress = 0;
                        if (data.success) {
                            // add the new videos into the existing video array
                            let audios = this.files.concat(data.audios);
                            this.$emit('update:files', audios);
                        }
                        else {
                            alert(data);
                        }
                    }
                    , (progress) => {
                        this.uploadProgress = progress;
                    }
                );
            }
        },
        onClickFile(e, i) {
            e.preventDefault();

            if (this.loading) {
                return;
            }

            let file = this.files[i];
            this.selectedFile = file;
            $('#file-modal-' + this.id).modal('show');
        },
        prevFile() {
            let index = this.files.indexOf(this.selectedFile);
            if (index == 0) {
                return;
            }
            this.selectedFile = this.files[index - 1];
        },
        nextFile() {
            let index = this.files.indexOf(this.selectedFile);
            if (index == this.files.length - 1) {
                return;
            }
            this.selectedFile = this.files[index + 1];
        },
        getThumbnail(src) {

            if (this.type == "images") {

                let extension = src.split('.').pop();

                // check if extension is not in filesWhoHasThumb
                if (this.filesWhoHasThumb.indexOf(extension) == -1) {
                    return src;
                }

                let img_thumb_url = src.replace('/storage/images/', '/storage/images/tn_').replace('/storage/old/', '/storage/old/tn_');

                return src.replace('/storage/images/', '/storage/images/tn_').replace('/storage/old/', '/storage/old/tn_');

            } else if (this.type == "videos") {
                return "";
                // extract thumbnail from the video using cannvas
                let video = document.createElement('video');
                video.src = src;

                let canvas = document.createElement('canvas');

                video.addEventListener('loadeddata', function () {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

                    console.log(canvas.toDataURL());
                });

                return canvas.toDataURL();

            }

        }
    },
};

