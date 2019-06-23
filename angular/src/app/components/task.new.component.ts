import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../services/user.service';

@Component({
  selector: 'task-new', // etiqueta html para el componente
  templateUrl: '../views/task.new.html',
  providers: [UserService]
})
// permitir usar el componente en otros ficheros
export class TaskNewComponent implements OnInit{

  public title: string;
  public identity;
  public token;
  
  constructor(private _route: ActivatedRoute, private _router: Router, private _userService: UserService){
      // se pueden crear variable que en HTML pueden imprimirse por interpolación {{}}
      this.title = 'Crear nueva tarea';
      this.identity = this._userService.getIdentity();
      this.token = this._userService.getToken();
  }

  ngOnInit() {
    console.log('Componente task.new.component cargado');
    // regirigimos a la ventana de login si no tenemos datos del usuario logueado
    if (!this.identity) {
        this._router.navigate(['/login']);
    // de otra forma rellenamos el objeto user con los datos del identity
    }
  }
}


