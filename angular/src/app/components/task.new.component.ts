import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../services/user.service';
import { TaskService } from '../services/task.service';
import { Task } from '../models/task';

@Component({
  selector: 'task-new', // etiqueta html para el componente
  templateUrl: '../views/task.new.html',
  providers: [TaskService, TaskService]
})
// permitir usar el componente en otros ficheros
export class TaskNewComponent implements OnInit{

  public page_title: string;
  public identity;
  public token;
  public task: Task;
  
  constructor(
    private _route: ActivatedRoute, 
    private _router: Router, 
    private _userService: UserService,
    private _taskService: TaskService){
      // se pueden crear variable que en HTML pueden imprimirse por interpolación {{}}
      this.page_title = 'Crear nueva tarea';
      this.identity = this._userService.getIdentity();
      this.token = this._userService.getToken();
  }

  ngOnInit() {
    console.log('Componente task.new.component cargado');
    // regirigimos a la ventana de login si no tenemos datos del usuario logueado
    if (!this.identity && !this.identity.sub) {
        this._router.navigate(['/login']);
    // de otra forma rellenamos el objeto task
    } else {
      this.task = new Task(null,'','','new',null,null);
    }
    console.log(this._taskService.create(this.task));
  }

  // se hace la llamada al servido de cración de tarea
  onSubmit() {
    console.log(this.task);
  }
}


