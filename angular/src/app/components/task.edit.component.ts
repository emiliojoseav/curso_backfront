import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../services/user.service';
import { TaskService } from '../services/task.service';
import { Task } from '../models/task';

@Component({
  selector: 'task-edit', // etiqueta html para el componente
  templateUrl: '../views/task.new.html', // se reutiliza la vista de creación de tarea
  providers: [TaskService, TaskService]
})
// permitir usar el componente en otros ficheros
export class TaskEditComponent implements OnInit{

  public page_title: string;
  public identity;
  public token;
  public task: Task;
  public status_task;
  public loading;
  public successMsg = '¡Tarea editada correctamente!';
  public failMsg = '¡Error en la edición de la tarea!';
  
  constructor(
    private _route: ActivatedRoute, 
    private _router: Router, 
    private _userService: UserService,
    private _taskService: TaskService){
      // se pueden crear variable que en HTML pueden imprimirse por interpolación {{}}
      this.page_title = 'Modificar tarea';
      this.identity = this._userService.getIdentity();
      this.token = this._userService.getToken();
  }

  ngOnInit() {
    console.log('Componente task.new.component cargado');
    // regirigimos a la ventana de login si no tenemos datos del usuario logueado
    if (!this.identity && !this.identity.sub) {
        this._router.navigate(['/login']);
    // de otra forma rellenamos el objeto task con la peticion ajax
    } else {
      // inicializamos la tarea para evitar errores con el html y el twoWayDataBinding
      this.task = new Task(null,null,null,null,null,null);
      // se obtienen los datos de la tarea
      this.getTask();
    }
  }

  // se hace la llamada al servido de cración de tarea
  onSubmit() {
    console.log(this.task);
    // realizamos la petición
    this._route.params.forEach((params: Params) => {
        let id = +params['id']; // se recoge el id como entero
        this._taskService.update(this.token, this.task, id).
        // tratamos la respuesta asíncrona
        subscribe(response => {
          console.log(response);
            this.status_task = response.status;
            if (this.status_task != 'success') {
            this.status_task = 'error';
            } else {
            this.task = response.data;
            // redirigimos al home
            this._router.navigate(['/']);
            }
        },
        error => {
            console.log(<any>error);
        }
        );
    });
  }

  getTask() {
    this.loading = 'show';
    this._route.params.forEach((params: Params) => {
        let id = +params['id']; // se recoge el id como entero
        this._taskService.getTask(this.token, id).
        // tratamos la respuesta asíncrona
        subscribe(response => {
            // verificar que la tarea es nuestra para poder verla
            if (response.status == 'success') {
                if (response.data.user.id == this.identity.sub) {
                    this.task = response.data;
                    this.loading = 'hide';
                } else {
                    this._router.navigate(['/']);
                }
            } else {
                this._router.navigate(['/login']);
            }
        },
        error => {
            console.log(<any>error);
        }
        );
    });
}
}


