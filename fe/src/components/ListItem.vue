<script setup>
defineProps({
  id: {
    required: true
  },
  name: {
    required: true
  },
  person: {
    required: true
  },
  description: {
    required: true
  },
  date: {
    default: null
  }
})
</script>

<script>
export default {
  data() {
    return {
      checked2: '__.__.__'
    }
  },
  methods: {
    updateItem(d_id) {
      this.$emit('update_item', d_id)
    },
    deleteItem(d_id) {
      fetch('http://todo.localhost/api/delete', {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json'
          // like application/json or text/xml
        },
        body: JSON.stringify({
          // Example: Update JSON file with
          //          local data properties
          id: d_id
          // etc.
        })
      }).then(response => response.json()).then(data => {
        if (data.error === 0) {
          this.$emit('recompute')
        }
      })
    },
    async endTask(t_id, the_done){

      await fetch('http://todo.localhost/api/tasks', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
          // like application/json or text/xml
        },
        body: JSON.stringify({
          // Example: Update JSON file with
          //          local data properties
          done: !the_done,
          id: t_id,
          // etc.
        })
      })
      this.$emit('recompute')

    }
  },
  computed: {
    get_done(){
      if (this.date == null){
        return false
      }
      return true
    }
  }

}
</script>
<template>
  <b-list-group-item :id="id" class="flex-column align-items-start">

    <b-row>
      <b-col cols="8">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1">{{ name }}</h5>
          <small class="text-muted">{{ date }}</small>
        </div>

        <p class="mb-1">
          {{ description }}
        </p>

        <small class="text-muted">{{ person }}</small>
      </b-col>
      <b-col class="text-end m-auto">
        <b-form-checkbox
            id="checkbox-1"
            v-model="get_done"
            name="checkbox-1"
            value=""
            unchecked-value="not_accepted" @change="endTask(id, get_done)">DONE
        </b-form-checkbox>
        <b-button style="color: #ffffff" class="me-3" @click="updateItem(id)" v-b-modal="'modal-edit'"
                  variant="warning">Edit
        </b-button>
        <b-button @click="deleteItem(id)" variant="danger">Delete</b-button>
      </b-col>
    </b-row>

  </b-list-group-item>
</template>

<style>

.form-check {
  display: inline !important;
  margin-right: 20px;
}

.form-check input {
  margin-top: 10px;
  float: unset !important;
  margin-right: 5px !important;
}

</style>