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
export class DefaultComponent implements OnInit {
  public title: string;
  public identity;
  public token;
  public tasks: Array<Task>;
  public loading;
  // variables de paginación
  public pages;
  public pagePrev;
  public pageNext;
  // variables para la búsqueda
  public filter = 0;
  public order = 0;
  public searchString;

  constructor(private _route: ActivatedRoute,
    private _router: Router,
    private _userService: UserService,
    private _taskService: TaskService) {
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
  getAllTasks() {
    this._route.params.forEach((params: Params) => {
      let page = +params['page']; // con + se convierte a integer el parámetro
      if (!page) {
        page = 1;
      }
      this.loading = 'show';
      this._taskService.getTasks(this.token, page).
        // tratamos la respuesta asíncrona
        subscribe(response => {
          if (response.status == 'success') {
            this.tasks = response.data;
            this.loading = 'hide';
            // console.log(this.tasks);

            // total de páginas recibidas
            this.pages = [];
            for (let i = 0; i < response.total_pages; i++) {
              this.pages.push(i);
            }
            // página anterior
            if (page >= 2) {
              this.pagePrev = (page - 1);
            } else {
              this.pagePrev = page;
            }
            // página siguiente
            if (page < response.total_pages) {
              this.pageNext = (page + 1);
            } else {
              this.pageNext = page;
            }
          }
        },
          error => {
            console.log(<any>error);
          }
        );
    });
  }

  // búsqueda de tarea
  search() {
    this.loading = 'show';
    // si no disponemos de cadena de búsqueda
    if (!this.searchString || this.searchString.trim().length == 0) {
      this.searchString = null;
    }
    this._taskService.search(this.token, this.searchString, this.filter, this.order).
      // tratamos la respuesta asíncrona
      subscribe(response => {
        if (response.status == 'success') {
          this.tasks = response.data;
          this.loading = 'hide';
        // si la búsqueda falla, redirección a 
        } else {
          this._router.navigate(['/index']);
        }
      },
      error => {
        console.log(<any>error);
      });
  }
}
