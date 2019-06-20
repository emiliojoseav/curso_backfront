import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';

@Component({
  selector: 'default', // etiqueta html para el componente
  templateUrl: '../views/default.html'
})
// permitir usar el componente en otros ficheros
export class DefaultComponent implements OnInit{
  public title: string;
  
  constructor(/*private _route: ActivatedRoute, private _router: Router*/){
      // se pueden crear variable que en HTML pueden imprimirse por interpolación {{}}
      this.title = 'Home page';
  }
  
  ngOnInit() {
    console.log('Componente default.component cargado');
  }
}
