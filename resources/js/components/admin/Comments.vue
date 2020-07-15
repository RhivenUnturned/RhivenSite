<template>
  <div class="card my-4">
    <h5 class="card-header">Ajouter un commentaire:</h5>
    <div class="card-body">
      <form>
        <div class="form-group">
          <label for="body" class="sr-only">Écrire un commentaire</label>
          <textarea id="body" ref="body" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" @click.prevent="addComment" class="btn btn-primary">Envoyer</button>
      </form>
    </div>
    <p class="border p-3" v-for="comment in comments" :key="comment">
      <strong>{{ comment.user.name }}</strong>:
      <span>{{ comment.body }}</span>
    </p>
  </div>
</template>

<script>
export default {
  props: {
    userName: {
      type: String,
      required: true
    },
    postId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      comments: []
    };
  },

  created() {
    this.fetchComments();

    Echo.private("comment").listen("CommentSent", e => {
      this.comments.push({
        user: { name: e.user.name },
        body: e.comment.body
      });
    });
  },

  methods: {
    fetchComments() {
      axios.get("/" + this.postId + "/comments").then(response => {
        this.comments = response.data;
      });
    },

    addComment() {
      let body = this.$refs.body.value;
      axios.post("/" + this.postId + "/comments", { body }).then(response => {
        this.comments.push({
          user: { name: this.userName },
          body: this.$refs.body.value
        });
        this.$refs.body.value = "";
      });
    }
  }
};
</script>
