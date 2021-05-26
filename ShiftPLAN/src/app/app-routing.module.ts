/**
 * app-routing.module.ts
 * 
 * Angular module specifying the navigation routes 
 * for our application.
 * 
 * author: Sascha W.
 * last edit / by: 2021-05-22 / Sascha W.
 */

import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

const routes: Routes = [];

@NgModule({
  imports: [RouterModule.forRoot(routes, {useHash: true})],
  exports: [RouterModule]
})
export class AppRoutingModule { }
