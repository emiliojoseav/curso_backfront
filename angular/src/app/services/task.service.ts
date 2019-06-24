// Crear un servicio
import {Injectable} from '@angular/core';
import {Http, Response, Headers} from '@angular/http';
import "rxjs/add/operator/map"; // captura la respuesta del servicio REST
import {map} from 'rxjs/operators';
import {Observable} from 'rxjs/Observable';
import {GLOBAL} from './global';

@Injectable()
/**
 * Servicio para realizar las peticiones de login a la API REST del servidor
 */
export class TaskService {

	public url: string;

	constructor (private _http: Http) {
		this.url = GLOBAL.url;
	}

	// peticion ajax a la ruta "/task/new" de la API REST
	create (task_to_create) {
		return "servicio de creación de tarea";
		// let json = JSON.stringify(user_to_login);
		// console.log(json);
		// let params = "json=" + json;
		// let headers = new Headers({'Content-Type':'application/x-www-form-urlencoded'}); //la petición se envía como formulario
        //        //petición a la url de login del server(symfony)               // respuesta
		// return this._http.post(this.url+'/login', params, {headers: headers}).map(res => res.json());
	}
}