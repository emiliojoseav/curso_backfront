import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

// importamos los componentes que hemos creado
import { LoginComponent } from './components/login.component';
import { RegisterComponent } from './components/register.component';
import { DefaultComponent } from './components/default.component';
import { UserEditComponent } from './components/user.edit.component';
import { TaskNewComponent } from './components/task.new.component';
import { TaskDetailComponent } from './components/task.detail.component';
import { TaskEditComponent } from './components/task.edit.component';

// creamos rutas
const appRoutes: Routes= [
	{path:'',component: DefaultComponent}, // con path vacío carga por defecto el DefaultComponent
	{path:'index',component: DefaultComponent},
	{path:'index/:page',component: DefaultComponent}, // se indica la página de tareas
	{path:'login',component: LoginComponent},
	{path:'login/:id',component: LoginComponent}, // se indica que la ruta puede recibir un parámetro
	{path:'register',component: RegisterComponent},
	{path:'user-edit',component: UserEditComponent},
	{path:'task-new',component: TaskNewComponent},
	{path:'task/:id',component: TaskDetailComponent}, // ruta con el id de la tarea a mostrar
	{path:'task-edit/:id',component: TaskEditComponent}, // ruta con el id de la tarea a editar
	{path:'**',component: LoginComponent} // cualquier otra ruta carga LoginComponent
];

// exportamos variables
export const appRoutingProviders: any[] = []; // como array vacío de cualquier cosa(any)
export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes); // se exportan las rutas creadas