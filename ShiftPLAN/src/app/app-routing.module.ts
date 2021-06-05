/**
 * app-routing.module.ts
 * 
 * Angular module specifying the navigation routes 
 * for our application.
 * 
 * author: Sascha W.
 * last edit / by: 2021-06-05 / Anne Naumann
 */

import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  { path: 'app', loadChildren: () => import('./modules/main/main.module').then(m => m.MainModule) } 
];

@NgModule({
  imports: [RouterModule.forRoot(routes, {useHash: true})],
  exports: [RouterModule]
})
export class AppRoutingModule { }
