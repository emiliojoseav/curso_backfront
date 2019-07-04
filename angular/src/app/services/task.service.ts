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
	create (token, task) {
		let json = JSON.stringify(task);
		console.log(json);
		let params = "json=" + json + "&authorization=" + token;
		let headers = new Headers({'Content-Type':'application/x-www-form-urlencoded'}); //la petición se envía como formulario
               //petición a la url de login del server(symfony)                  // respuesta
		return this._http.post(this.url+'/task/new', params, {headers: headers}).map(res => res.json());
	}

	// listado de tareas
	getTasks(token, page = null) {
		let params = "authorization=" + token;
		let headers = new Headers({'Content-Type':'application/x-www-form-urlencoded'}); //la petición se envía como formulario
		if (!page) {
			page = 1;
		}
               //petición a la url de login del server(symfony)                                // respuesta
		return this._http.post(this.url+'/task/list?page=' + page, params, {headers: headers}).map(res => res.json());
	}

	// detalle de tarea
	getTask(token, id) {
		let params = "authorization=" + token;
		let headers = new Headers({'Content-Type':'application/x-www-form-urlencoded'}); //la petición se envía como formulario
                          //petición a la url de login del server(symfony)
		return this._http.post(this.url+'/task/detail/' + id, params, {headers: headers})
						  // respuesta
						 .map(res => res.json());
	}

	// actualizar tarea
	update(token, task, id) {
		let json = JSON.stringify(task);
		let params = "json=" + json + "&authorization=" + token;
		let headers = new Headers({'Content-Type':'application/x-www-form-urlencoded'}); //la petición se envía como formulario
                          //petición a la url de login del server(symfony)
		return this._http.post(this.url+'/task/edit/' + id, params, {headers: headers})
						  // respuesta
						 .map(res => res.json());
	}
}