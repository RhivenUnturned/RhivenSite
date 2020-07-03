<template>
    <div class="container">
        <form>
            <div
                :class="[
                    'form-group m-1 p-3',
                    successful ? 'alert-success' : ''
                ]"
            >
                <span v-if="successful" class="label label-sucess"
                    >Modifié!</span
                >
            </div>

            <div :class="['form-group m-1 p-3', error ? 'alert-danger' : '']">
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
export default {
    mounted() {
        this.getPost();

        this.editor = new Quill(this.$refs.editor, {
            modules: {
                toolbar: [
                    ["bold", "italic", "underline"],
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
                    ]
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
          editor: null,
            error: false,
            successful: false,
            errors: []
        };
    },
    methods: {
        update() {
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
        }
    }
};
</script>
