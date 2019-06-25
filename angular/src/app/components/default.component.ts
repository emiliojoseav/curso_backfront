import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../services/user.service';
import { TaskService } from '../services/task.service';
import { Task } from '../models/task';

@Component({
  selector: 'default', // etiqueta html para el componente
  templateUrl: '../views/default.html',
  providers: [TaskService, TaskService]
})
// permitir usar el componente en otros ficheros
export class DefaultComponent implements OnInit{
  public title: string;
  public identity;
  public token;
  public tasks:Array<Task>;
  
  constructor(private _route: ActivatedRoute, 
    private _router: Router, 
    private _userService: UserService,
    private _taskService: TaskService)
    {
      // se pueden crear variable que en HTML pueden imprimirse por interpolación {{}}
      this.title = 'Home page';
      this.identity = this._userService.getIdentity();
      this.token = this._userService.getToken();
  }
  
  ngOnInit() {
    console.log('Componente default.component cargado');
    this.getAllTasks();
  }

  // obteners los parámetros de la url para saber en qué página estamos
  getAllTasks () {
    this._route.params.forEach((params: Params) => {
      let page = +params['page']; // con + se convierte a integer el parámetro
      if (!page) {
        page = 1;
      }
      this._taskService.getTasks(this.token, page).
        // tratamos la respuesta asíncrona
        subscribe(response => {
          if (response.status == 'success') {
            this.tasks = response.data;
            console.log(this.tasks);
          // se actualiza el el identity guardado en loclaSotrage
          }
        },
        error => {
          console.log(<any>error);
        }
      );
    });
  }
}
