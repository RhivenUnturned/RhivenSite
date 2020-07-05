<template>
    <div>
        <h1>Écrire un nouvel article pour le blog</h1>
        <hr />
        <form>
            <div
                :class="[
                    'form-group m-1',
                    successful ? 'alert-success p-3' : 'invisible'
                ]"
            >
                <span v-if="successful" class="label label-sucess"
                    >Publié!</span
                >
            </div>
            <div
                :class="[
                    'form-group m-3',
                    error ? 'alert-danger p-3' : 'invisible'
                ]"
            >
                <span v-if="errors.title" class="label label-danger">{{
                    errors.title[0]
                }}</span>
                <span v-if="errors.body" class="label label-danger">{{
                    errors.body[0]
                }}</span>
                <span v-if="errors.image" class="label label-danger">{{
                    errors.image[0]
                }}</span>
            </div>

            <div class="form-group">
                <input
                    type="text"
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
                    type="text"
                    ref="alt"
                    class="form-control"
                    id="alt"
                    placeholder="Entrer une description"
                    @keydown.enter.prevent="closeAlt"
                    @keyup="setAlt"
                />
            </div>

            <div class="custom-file mb-3">
                <input
                    type="file"
                    ref="image"
                    name="image"
                    class="custom-file-input"
                    id="image"
                />
                <label
                    class="custom-file-label"
                    for="image"
                    data-browse="Parcourir"
                    >Choisir un fichier...</label
                >
            </div>

            <button
                type="submit"
                @click.prevent="create"
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

        // We will add the update event here
        this.editor.on("text-change", () => this.update());

        /* show file value after file select */
        document
            .querySelector(".custom-file-input")
            .addEventListener("change", function(e) {
                var fileName = document.getElementById("image").files[0].name;
                var nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            });
    },
    props: {
        userId: {
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
        create() {
            const formData = new FormData();
            formData.append("title", this.$refs.title.value);
            formData.append("body", this.editor.root.innerHTML);
            formData.append("quill", JSON.stringify(this.editor.getContents()));
            formData.append("user_id", this.userId);
            formData.append("image", this.$refs.image.files[0]);

            axios
                .post("/api/posts", formData)
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

            this.$refs.title.value = "";
            this.editor.root.innerHTML = "";
        },
        update() {
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
