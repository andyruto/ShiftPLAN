/**
 * tasks-add.component.spec
 * 
 * Typescript class containing the tests for the 
 * tasks-add component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-15 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { TasksAddComponent } from './tasks-add.component';

describe('TasksAddComponent', () => {
  let component: TasksAddComponent;
  let fixture: ComponentFixture<TasksAddComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ TasksAddComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(TasksAddComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
