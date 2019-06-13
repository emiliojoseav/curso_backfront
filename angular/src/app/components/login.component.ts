import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';

@Component({
  selector: 'login', // etiqueta html para el componente
  templateUrl: '../views/login.html'
})
// permitir usar el componente en otros ficheros
export class LoginComponent implements OnInit{
  public title: string;
  
  constructor(/*private _route: ActivatedRoute, private _router: Router*/){
      // se pueden crear variable que en HTML pueden imprimirse por interpolación {{}}
      this.title = 'Componente de login';
  }
  ngOnInit() {
    console.log('Componente login.component cargado');
  }
}


