import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../services/user.service';
import { TaskService } from '../services/task.service';
import { Task } from '../models/task';

@Component({
    selector: 'task-detail', // etiqueta html para el componente
    templateUrl: '../views/task.detail.html',
    providers: [TaskService, TaskService]
})
// permitir usar el componente en otros ficheros
export class TaskDetailComponent implements OnInit {

    public identity;
    public token;
    public task;
    public loading;

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _userService: UserService,
        private _taskService: TaskService) {
        this.identity = this._userService.getIdentity();
        this.token = this._userService.getToken();
    }

    ngOnInit() {
        console.log('Componente task.detail.component cargado');
        // regirigimos a la ventana de login si no tenemos datos del usuario logueado
        if (!this.identity || !this.identity.sub) {
            this._router.navigate(['/login']);
        // llamar al servicio de tareas para obtener la info
        } else {
            this.getTask();
        }
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


