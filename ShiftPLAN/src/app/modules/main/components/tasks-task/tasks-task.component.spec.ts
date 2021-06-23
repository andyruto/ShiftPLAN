/**
 * tasks-task.component.spec
 * 
 * Typescript class containing the tests for the 
 * tasks-task component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-15 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { TasksTaskComponent } from './tasks-task.component';

describe('TasksTaskComponent', () => {
  let component: TasksTaskComponent;
  let fixture: ComponentFixture<TasksTaskComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ TasksTaskComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(TasksTaskComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
