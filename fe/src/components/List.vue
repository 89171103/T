<style>
.modal-dialog {
  z-index: 1100;
}
</style>

<script setup>
import ListItem from './ListItem.vue'
</script>

<script>
import $ from 'jquery'
export default {
  data() {
    return {
      form: {
        task: '',
        person: '',
        desc: ''
      },
      show: true,
      edit_task_name: '',
      edit_task_person: '',
      edit_task_desc: '',
      edit_task_id: '',
      pages: 0,
      perPage: 4,
      currentPage: 1,
      //fields: ['name', 'person', 'description', 'end_date'],
      items: [
        /*
      {name: 'Do this', end_date: '24.5.2022', person: 'Random', description: ''},
      {name: 'Do that', end_date: '24.5.2022', person: 'Shaw', description: ''},
      {name: 'Do you', end_date: '24.5.2022', person: 'Wilson', description: ''},
      {name: 'Do it', end_date: '24.5.2022', person: 'Carney', description: ''},
      {name: 'Just do it', end_date: '24.5.2022', person: 'Random', description: ''},
      {name: 'Home alone', end_date: '24.5.2022', person: 'Shaw', description: ''},
      {name: 'Macaulay Culkin', end_date: '24.5.2022', person: 'Wilson', description: ''},
      {name: 'Ko', end_date: '24.5.2022', person: 'Carney', description: ''},
      {name: 'Ko', end_date: '24.5.2022', person: 'Carney', description: ''}*/
      ]
    }
  },
  computed: {
    rows() {
      return this.items.length
    },
    shown_items() {
      return this.items.filter((item, index) => index < this.currentPage * this.perPage && index >= (this.currentPage - 1) * this.perPage)
    }
  },
  methods: {

    onSubmit(event) {
      event.preventDefault()
      fetch('http://todo.localhost/api/tasks', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
          // like application/json or text/xml
        },
        body: JSON.stringify({
          // Example: Update JSON file with
          //          local data properties
          name: this.form.task,
          person: this.form.person,
          description: this.form.desc
          // etc.
        })
      }).then(() => {
            this.form.task = ''
            this.form.person = ''
            this.form.desc = ''
          }
      ).then(this.get_posts).then($(".btn-close").trigger('click'))


      /*
      * .then(response => response.json()).then(data => {
        console.log(data.message)
      })*/
    },
    onReset(event) {
      event.preventDefault() // do not allow form to reload :)
      // Reset our form values
      this.form.task = ''
      this.form.person = ''
      this.form.desc = ''
      // Trick to reset/clear native browser form validation state
      this.show = false // hide the form than show it again :D :D
      this.$nextTick(() => {
        this.show = true
      })
    },
    updateTask() {
      if (this.edit_task_id > 0) {
        fetch('http://todo.localhost/api/tasks', {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
            // like application/json or text/xml
          },
          body: JSON.stringify({
            // Example: Update JSON file with
            //          local data properties
            name: this.edit_task_name,
            person: this.edit_task_person,
            description: this.edit_task_desc,
            id: this.edit_task_id
            // etc.
          })
        }).then(this.get_posts).then(() => {
          $(".btn-close").trigger('click') // VERSIONS !$@#@# !bvModal !jquery
        })

        /*  .then(response => response.json()).then(data => {
        if(data.error = 0) {
          this.get_posts()
        }
      })*/
      }
    },
    updateT(d_id) {
      fetch('http://todo.localhost/api/tasks?id=' + d_id, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json'
        }
      }).then(response => response.json()).then(data => {
        this.edit_task_desc = data[0].desc
        this.edit_task_name = data[0].name
        this.edit_task_person = data[0].person
        this.edit_task_id = data[0].id
      })

    },
    get_posts() {
      fetch('http://todo.localhost/api/tasks', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json'
        }
      }).then(response => response.json()).then(data => {
        this.items = data.data
        if (this.currentPage > Math.ceil(this.rows / this.perPage)) {
          this.currentPage = this.currentPage - 1
        }
      })

    }
  }, beforeMount() {
    this.get_posts()
  }
}
</script>

<template>

  <b-row>
    <div class="text-center mt-3">
      <b-button variant="primary" v-b-modal="'modal-add'">Add</b-button>

      <b-modal id="modal-add" hide-footer="" title="Add">
        <b-form @submit="onSubmit" @reset="onReset" v-if="show">

          <b-form-group id="input-group-2" label="Naziv:" label-for="input-2">
            <b-form-input
                id="input-2"
                v-model="form.task"
                placeholder="Vnesi Naziv"
                required
            ></b-form-input>
          </b-form-group>

          <b-form-group id="input-group-2" label="Oseba:" label-for="input-2">
            <b-form-input
                id="input-2"
                v-model="form.person"
                placeholder="Oseba"
                required
            ></b-form-input>
          </b-form-group>

          <b-form-group id="input-group-2" label="Opis:" label-for="input-2">
            <b-form-input
                id="input-2"
                v-model="form.desc"
                placeholder="Opis"
            ></b-form-input>
          </b-form-group>

          <b-button type="submit" variant="primary">Submit</b-button>
          <b-button type="reset" variant="danger">Reset</b-button>
        </b-form>
        <b-card class="mt-3" header="Form Serialize">
          <pre class="m-0">{{ form }}</pre>
        </b-card>
      </b-modal>

    </div>

  </b-row>
  <b-row>
    <b-pagination class="justify-content-center mt-3"
                  v-model="currentPage"
                  :total-rows="rows"
                  :per-page="perPage"
                  aria-controls="todo">
    </b-pagination>

    <!--  <b-table id="todo" striped hover :items="shown_items" :per-page="perPage"
               :current-page="currentPage" :fields="fields"></b-table>-->

    <!--   <b-button :disabled="currentPage <= 1" @click="currentPage&#45;&#45;" variant="outline-primary">Previos</b-button>
      <span class="m-3">Page: {{ currentPage }} of {{Math.ceil(rows/perPage)}}</span>
      <b-button :disabled="currentPage >= rows/perPage" @click="currentPage++" variant="outline-primary">Next</b-button>
           <b-button @click="get_posts()" variant="outline-primary">Next</b-button>-->

    <b-list-group>

      <ListItem v-on:recompute="get_posts" v-on:update_item="updateT($event)" v-for="item in shown_items" :id="item.id"
                :name="item.name"
                :person="item.person" :description="item.desc" :date="item.date"/>

      <!--    <b-list-group-item v-for="item in shown_items" class="flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">{{item.name}}</h5>
              <small class="text-muted">3 days ago</small>
            </div>

            <p class="mb-1">
              Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.
            </p>

            <small class="text-muted">Donec id elit non mi porta.</small>
          </b-list-group-item>-->

    </b-list-group>

    <b-modal ref="edit-modal" hide-footer="" id="modal-edit" title="Edit">
      <b-form>

        <b-form-group id="input-group-2" label="Naziv:" label-for="input-2">
          <b-form-input
              id="input-2"
              v-model="this.edit_task_name"
              placeholder="Naziv"
              required
          ></b-form-input>
        </b-form-group>

        <b-form-group id="input-group-2" label="Oseba:" label-for="input-2">
          <b-form-input
              id="input-2"
              v-model="this.edit_task_person"
              placeholder="Oseba"
              required
          ></b-form-input>
        </b-form-group>

        <b-form-group id="input-group-2" label="Opis:" label-for="input-2">
          <b-form-input
              id="input-2"
              v-model="this.edit_task_desc"
              placeholder="Opis"
          ></b-form-input>
        </b-form-group>

        <b-button @click="updateTask" variant="primary">Save</b-button>
      </b-form>
    </b-modal>

  </b-row>
</template>