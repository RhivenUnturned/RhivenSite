<template>
    <div class="container">
        <form>
            <div
                :class="[
                    'form-group m-1',
                    successful ? 'alert-success p-3' : 'invisible'
                ]"
            >
                <span v-if="successful" class="label label-sucess"
                    >Modifié!</span
                >
            </div>

            <div
                :class="[
                    'form-group m-1',
                    error ? 'alert-danger p-3' : 'invisible'
                ]"
            >
                <span v-if="errors.title" class="label label-danger">{{
                    errors.title[0]
                }}</span>
                <span v-if="errors.body" class="label label-danger">{{
                    errors.body[0]
                }}</span>
            </div>

            <div class="form-group">
                <input
                    type="title"
                    ref="title"
                    class="form-control"
                    id="title"
                    placeholder="Entrer un titre"
                    required
                    @keydown.enter.prevent
                />
            </div>

            <div class="my-2 position-relative border rounded">
                <div
                    id="editorcontainer"
                    style="height:25em; min-height:100%; overflow-y:auto;margin-top:3em"
                >
                    <div
                        ref="editor"
                        style="min-height:100%; height:auto;"
                    ></div>
                </div>
            </div>

            <div class="form-group invisible">
                <input
                    type="alt"
                    ref="alt"
                    class="form-control"
                    id="alt"
                    placeholder="Entrer une description"
                    @keydown.enter.prevent="closeAlt"
                    @keyup="setAlt"
                />
            </div>

            <button
                type="submit"
                @click.prevent="update"
                class="btn btn-primary block"
            >
                Envoyer
            </button>
        </form>
    </div>
</template>
<script>
import Quill from "quill";
import Parchment from "parchment";
import "quill/dist/quill.snow.css";
import ImageUploader from "quill-image-uploader";
export default {
    mounted() {
        Quill.register("modules/imageUploader", ImageUploader);

        this.getPost();

        document.addEventListener("click", e => {
            if (e.target.tagName.toUpperCase() === "IMG") {
                this.image = Quill.find(e.target);

                this.$refs.alt.value = this.image.domNode.alt;
                this.$refs.alt.parentNode.classList.remove("invisible");
            } else if (e.target === this.$refs.alt) {
            } else if (
                !this.$refs.alt.parentNode.classList.contains("invisible")
            ) {
                this.$refs.alt.parentNode.classList.add("invisible");
            }
        });

        let Embed = Quill.import("blots/block/embed");
        class Picture extends Embed {
            static create(value) {
                let node = super.create(value);
                return node;
            }
        }
        Picture.blotName = "picture";
        Picture.tagName = "picture";
        Quill.register({ "formats/picture": Picture });

        this.editor = new Quill(this.$refs.editor, {
            modules: {
                imageUploader: {
                    upload: file => {
                        return new Promise((resolve, reject) => {
                            const formData = new FormData();
                            formData.append("image", file);

                            fetch(
                                "https://api.imgbb.com/1/upload?key=1fe8de127c43d082b9d15a4e7a977f1c",
                                {
                                    method: "POST",
                                    body: formData
                                }
                            )
                                .then(response => response.json())
                                .then(result => {
                                    console.log(result);
                                    resolve(result.data.url);
                                })
                                .catch(error => {
                                    reject("Upload failed");
                                    console.error("Error:", error);
                                });
                        });
                    }
                },
                toolbar: [
                    ["bold", "italic", "underline", "strike"],
                    [
                        {
                            color: []
                        },
                        {
                            background: []
                        },
                        {
                            font: []
                        },
                        {
                            size: []
                        },
                        {
                            align: []
                        }
                    ],
                    ["blockquote", "link", "image", "code-block"],
                    [
                        {
                            list: "ordered"
                        },
                        {
                            list: "bullet"
                        }
                    ],
                    ["clean"]
                ]
            },
            scrollingContainer: "#editorcontainer",
            theme: "snow"
        });

        this.editor.root.innerHTML = this.value;

        // We will add the update event here
        this.editor.on("text-change", () => this.updateQuill());
    },
    props: {
        postId: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            image: null,
            editor: null,
            error: false,
            successful: false,
            errors: []
        };
    },
    methods: {
        update() {
            let src,
                srcset,
                sizes = ["300", "480", "720", "810", "980"],
                html = this.editor.root,
                images = html.querySelectorAll(
                    "img:not([srcset]), img[srcset='']"
                ),
                notLazyImages = html.querySelectorAll(
                    "img:not([loading]), img[loading='']"
                ),
                link = "https://awkzqfrgoo.cloudimg.io/v7/";

            for (var i = 0; i < images.length; i++) {
                src = images[i].src;
                srcset = "";

                for (var j = 0; j < sizes.length; j++) {
                    srcset +=
                        link +
                        src +
                        "?width=" +
                        sizes[j] +
                        " " +
                        sizes[j] +
                        "w, ";
                }
                images[i].srcset = srcset;
            }

            for (var i = 0; i < notLazyImages.length; i++) {
                notLazyImages[i].setAttribute('loading', 'lazy');
            }

            let title = this.$refs.title.value;
            let body = this.editor.root.innerHTML;
            let quill = JSON.stringify(this.editor.getContents());

            axios
                .put("/api/posts/" + this.postId, { title, body, quill })
                .then(response => {
                    this.successful = true;
                    this.error = false;
                    this.errors = [];
                })
                .catch(error => {
                    if (!_.isEmpty(error.response)) {
                        if ((error.response.status = 422)) {
                            this.errors = error.response.data.errors;
                            this.successful = false;
                            this.error = true;
                        }
                    }
                });
        },
        getPost() {
            axios.get("/api/posts/" + this.postId).then(response => {
                this.$refs.title.value = response.data.data.title;
                this.editor.root.innerHTML = response.data.data.body;
            });
        },
        updateQuill() {
            this.$emit(
                "input",
                this.editor.getText() ? this.editor.root.innerHTML : ""
            );
        },
        setAlt(e) {
            this.image.format("alt", e.target.value);
        },
        closeAlt() {
            this.$refs.alt.parentNode.classList.add("invisible");
        }
    }
};
</script>
