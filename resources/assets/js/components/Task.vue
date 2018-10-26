<template>

   <div class="container">

       <div class="row">

           <div class="col-md-12">

               <div class="panel panel-default">

                   <div class="panel-heading">

                <h3><span class="glyphicon glyphicon-dashboard"></span> Tasks Dashboard </h3> <br>
                    <button @click="initAddTask()" class="btn btn-success " style="padding:5px">
                     Add New Task
                     </button>

                   </div>



                   <div class="panel-body">

              <table class="table table-bordered table-striped table-responsive" v-if="tasks.length > 0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"> Title</th>
                            <th scope="col"> Status</th>
                            <th scope="col"> Due Date </th>
                            <th scope="col">Action</th>
                         </tr>
                    </thead>
                   <tbody>

                           <tr v-for="(task, index) in tasks">
                               <th scope="row"> {{   index +1 }} </th>
                               <td> {{ task.title }} </td>
                               <td> {{ task.status }} </td>
                                <td> {{task.due_date }} </td>
                               <td>
 <button @click="initUpdate(index)" class="btn btn-success btn-xs" style="padding:8px">Edit</button>
 
 <button @click="deleteTask(index)" class="btn btn-danger btn-xs" style="padding:8px">Delete</button>

                               </td>

                           </tr>

                           </tbody>

                       </table>

                   </div>

               </div>

           </div>

       </div>



       <div class="modal fade" tabindex="-1" role="dialog" id="add_task_model">

           <div class="modal-dialog" role="document">

               <div class="modal-content">

                   <div class="modal-header">

                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span

                               aria-hidden="true">&times;</span></button>

                       <h4 class="modal-title">Add New Task</h4>

                   </div>

                   <div class="modal-body">



                       <div class="alert alert-danger" v-if="errors.length > 0">

                           <ul>

                               <li :v-for="error in errors">{{ error }}</li>

                           </ul>

                       </div>



                       <div class="form-group">

                           <label for="title">Title:</label>

                           <input type="text" name="title" id="title" placeholder="Task Title" class="form-control"

                                  v-model="task.title">

                       </div>
                        <div class="form-group">

                           <label for="status">Status:</label>
                            <select name="status" id="status" class="form-control" v-model="task.status">
                            <option value="pending">Pending</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="complete">Complete</option>
                            </select>

<!--                            <input type="text" name="status" id="status" placeholder="Status" class="form-control"

                                  v-model="task.status"> -->

                       </div>

                        <div class="form-group">

                           <label for="due_date">Due Date:</label>

                           <input type="date" name="due_date" id="due_date" placeholder="Due date" class="form-control"

                                  v-model="task.due_date">

                       </div>

                   </div>

                   <div class="modal-footer">

                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                       <button type="button" @click="createTask" class="btn btn-primary">Submit</button>

                   </div>

               </div><!-- /.modal-content -->

           </div><!-- /.modal-dialog -->

       </div><!-- /.modal -->



       <div class="modal fade" tabindex="-1" role="dialog" id="update_task_model">

           <div class="modal-dialog" role="document">

               <div class="modal-content">

                   <div class="modal-header">

                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span

                               aria-hidden="true">&times;</span></button>

                       <h4 class="modal-title">Update Task</h4>

                   </div>

                   <div class="modal-body">



                       <div class="alert alert-danger" v-if="errors.length > 0">

                           <ul>

                               <li v-for="error in errors">{{ error }}</li>

                           </ul>

                       </div>



                       <div class="form-group">

                           <label for="title">Title:</label>

                           <input type="text" name="title" id="title" placeholder="Task Title" class="form-control"

                                  v-model="update_task.title">

                       </div>
                        <div class="form-group">

                           <label for="status">Status:</label>
                            <select name="status" id="status" class="form-control" v-model="update_task.status">
                            <option value="pending">Pending</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="complete">Complete</option>
                            </select>

                       </div>

                        <div class="form-group">

                           <label for="due_date">Due Date:</label>

                           <input type="date" name="due_date" id="due_date" placeholder="Due date" class="form-control"

                                  v-model="update_task.due_date">

                       </div>

                   </div>

                   <div class="modal-footer">

                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                       <button type="button" @click="updateTask" class="btn btn-primary">Submit</button>

                   </div>

               </div><!-- /.modal-content -->

           </div><!-- /.modal-dialog -->

       </div><!-- /.modal -->



   </div>

</template>



<script>
export default {
  data() {
    return {
      task: {
        title: "",
        status: "",
        due_date: ""
      },

      errors: [],

      tasks: [],

      update_task: {}
    };
  },

  mounted() {
    this.readTasks();
  },

  methods: {
    deleteTask(index) {
      let conf = confirm("Do you ready want to delete this task?");

      if (conf === true) {
        axios
          .delete("/task/" + this.tasks[index].id)

          .then(response => {
            this.tasks.splice(index, 1);
          })

          .catch(error => {});
      }
    },

    initAddTask() {
      $("#add_task_model").modal("show");
    },

    createTask() {
      axios
        .post("/task", {
          title: this.task.title,

          status: this.task.status,

          due_date: this.task.due_date
        })

        .then(response => {
          this.reset();

          this.tasks.push(response.data.task);

          $("#add_task_model").modal("hide");
        })

        .catch(error => {
          this.errors = [];

          if (error.response.data.errors && error.response.data.errors.name) {
            this.errors.push(error.response.data.errors.name[0]);
          }

          if (
            error.response.data.errors &&
            error.response.data.errors.description
          ) {
            this.errors.push(error.response.data.errors.description[0]);
          }
        });
    },

    reset() {
      this.task.title = "";

      this.task.status = "";

      this.task.due_date = "";
    },

    readTasks() {
      axios
        .get("http://127.0.0.1:8000/task")

        .then(response => {
          this.tasks = response.data.tasks;
          //   console.log(response.data.tasks);
        });
    },

    initUpdate(index) {
      this.errors = [];

      $("#update_task_model").modal("show");

      this.update_task = this.tasks[index];
    },

    updateTask() {
      axios
        .patch("/task/" + this.update_task.id, {
          title: this.update_task.title,

          status: this.update_task.status,

          due_date: this.update_task.due_date
        })

        .then(response => {
          $("#update_task_model").modal("hide");
        })

        .catch(error => {
          this.errors = [];

          if (error.response.data.errors.name) {
            this.errors.push(error.response.data.errors.name[0]);
          }

          if (error.response.data.errors.description) {
            this.errors.push(error.response.data.errors.description[0]);
          }
        });
    }
  }
};
</script>